<?php namespace Crm\Services\PartnerLocator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Crm\Models\Partner;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 23.9.2015
 * Time: 9:17
 */


class PartnerLocator implements PartnerLocatorInterface
{

    private function getFilter(Request $request){

        $filter = [
            'radius' => '',
            'latitude' => '',
            'longitude' => '',
            'city_id' => ''
        ];

        //---- FILTERI
        if($request->get('radius') != ''){
            $filter['radius'] = $request->get('radius');
        }
        if($request->get('latitude') != ''){
            $filter['latitude'] = $request->get('latitude');
            session()->put('latitude', $filter['latitude']);
        }

        if($request->get('longitude') != ''){
            $filter['longitude'] = $request->get('longitude');
            session()->put('longitude', $filter['longitude']);
        }

        if($request->get('city_id') != ''){
            $filter['city_id'] = $request->get('city_id');
        }

        $filter['delatnost'] = $request->get('delatnost');

        return $filter;
    }


    public function nearest($latitude, $longitude, $radius, $partnerType, Array $pagination=[])
    {
        $x1 = str_replace(',','.',$latitude);
        $y1 = str_replace(',','.',$longitude);
        $partnerClass = Partner::class;
        $sqlSelect = "SELECT
						m.id as member_id,
						p.id as partner_id,
						p.erp_partner_id,
						p.description as name,
						p.description2 as department,
						p.phone,
						p.email,
						p.address,
						m.latitude,
						m.longitude,
						core_activity.value_integer as is_installer,
						m.city_name,
						m.postal_code,
						m.country_id as country_code,
						m.country_iso_alpha_2,
						m.country_iso_alpha_3,
						calculateDistance(COALESCE(m.latitude,0), COALESCE(m.longitude,0), ?, ?) AS distance,
						coalesce(project_rating.value_decimal,0) AS rating,
						coalesce(project_turnover.value_decimal,0) as turnover";


        $from  = " FROM delmax_crm.partner p
					  join delmax_crm.member m on m.membership_type=? and m.membership_id=p.id and m.project_id=2  and m.approved_at is not null and m.rejected_at is null
					  left join delmax_crm.member_information as core_activity on core_activity.member_id=m.id and core_activity.information_id=25
					  left join delmax_crm.member_information as project_turnover on project_turnover.member_id=m.id and project_turnover.information_id=26
					  left join delmax_crm.member_information as project_rating on project_rating.member_id=m.id and project_rating.information_id=27
					where ((core_activity.value_integer=?)or(2=?))
					and calculateDistance(coalesce(m.latitude,0), coalesce(m.longitude,0), ?, ?) <= ?*1.2";

        $sqlCount = " SELECT count(p.id) as rows_count " . $from;
        $sqlSelect .= $from;

        $sqlSelect .= ' ORDER BY project_turnover.value_decimal DESC';

        $sqlSelect .= " LIMIT ".($pagination['page']-1)*$pagination['results_per_page'].", ".$pagination['results_per_page'];

        $partners = DB::connection('CRM')->select($sqlSelect, [$x1, $y1, $partnerClass, $partnerType, $partnerType, $x1, $y1, $radius]);

        $count = DB::connection('CRM')->select($sqlCount, [$partnerClass, $partnerType, $partnerType, $x1, $y1, $radius]);

        $rows_count = 0;

        if (count($count)>0){
            $rows_count = $count[0]->rows_count;
        }
        return [
            'count' => $rows_count,
            'rows' => $partners
        ];
    }

    public function nearestByRequest(Request $request){
        $filter = $this->getFilter($request);
        $pagination['order'] = $request->get('order');
        $pagination['page']  = $request->get('page',1);
        $pagination['results_per_page'] = $request->get('results_per_page',10);
        $data = $this->nearest($filter['latitude'], $filter['longitude'], $filter['radius'], $filter['delatnost'], $pagination);
        $data = array_merge($data, $pagination);
        return $data;
    }
}
