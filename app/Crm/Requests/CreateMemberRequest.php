<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.10.2016
 * Time: 1:42
 */

namespace Crm\Requests;


use App\Http\Requests\Request;
use App\Models\User;
use Crm\Models\Partner;

class CreateMemberRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id'        => 'required|integer',
            'membership_type'   => 'required|max:250',
            'membership_id'     => 'required'
        ];
    }

    public function all($keys = null){

        $data= parent::all();

        $data = $this->getMemberData($data);

        return $data;
    }

    private function getMemberData($data){

        switch ($data['membership_type']){
            case Partner::class :
                $erpPartnerId = $data['erp_partner_id'];
                $erpCompanyId = $data['erp_company_id'];
                $partner = Partner::where(['erp_partner_id'=>$erpPartnerId, 'erp_company_id'=>$erpCompanyId])->first();
                if($partner){
                    $data['membership_id'] = $partner->id;

                    $data['name'] = $partner->description;

                    if (($partner->description2)&&(mb_strlen($partner->description2)>0)){
                        $data['name'] .= ' '.$partner->description2;
                    }
                }
                return $data;
                break;
            case User::class:
                $userId = $data['user_id'];
                $user = User::find($userId);
                if($user){
                    $data['membership_id'] = $userId;
                    $data['name'] = $user->first_name;
                    if (($user->last_name)&&(mb_strlen($user->last_name)>0)){
                        $data['name'] .= ' '.$user->last_name;
                    }
                }
                return $data;
                break;
            default:
                return '';
                break;
        }
    }
}