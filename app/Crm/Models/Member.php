<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:16
 */

namespace Crm\Models;


use App\Models\User;
use Crm\Appointments\Models\Provider;
use Crm\Appointments\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class Member extends Model
{
    protected $connection = 'CRM';

    protected $table = 'member';

    protected $primaryKey = 'id';

    protected $fillable= [
        'project_id', 'membership_type', 'membership_id',
        'applied_at', 'applied_by_person_id',
        'approved_at', 'approved_by_user_id',
        'rejected_at', 'rejected_by_user_id',
        'name', 'note',
        'address', 'city_id', 'postal_code', 'city_name',
        'country_id', 'country_name', 'country_iso_alpha_2', 'country_iso_alpha_3',
        'phone_number', 'email',
        'longitude', 'latitude', 'erp_company_id', 'erp_partner_id'
    ];

    public function membership(){

        return $this->morphTo();

    }

    public function addPartner(Partner $partner ){

    }

    public function addUser(User $user){

    }

    public function project(){

        return $this->belongsTo(Project::class, 'project_id');

    }

    public function paymentMethods(){

        return $this->belongsToMany(PaymentMethod::class)->whereNull('member_payment_method.deleted_at');

    }

    public function workingHours(){

        return $this->hasMany(MemberWorkingHour::class, 'member_id');

    }

    public function users(){

        return $this->belongsToMany(User::class, 'delmax_crm.member_user_role', 'member_id', 'user_id');

    }

    public function usersWithRoles($role){

        return MemberUserRole::join('fw_001.rbac_role as role', 'role.id', '=', 'member_user_role.role_id')
            ->join('delmaxapi.user', 'user.user_id', '=', 'member_user_role.user_id')
            ->where('member_user_role.member_id', '=', $this->id)
            ->where('role.name', '=', $role->name)
            ->select(
                'member_user_role.id',
                'member_user_role.role_id',
                'user.first_name',
                'user.last_name',
                'user.email',
                'user.phone_number',
                'role.name as role_name',
                'role.description as role_description')
            ->get();
    }

    public function appointmentsProviders(){

        return MemberUserRole::join('fw_001.rbac_role as role', 'role.id', '=', 'member_user_role.role_id')
            ->join('delmaxapi.user', 'user.user_id', '=', 'member_user_role.user_id')
            ->where('member_user_role.member_id', '=', $this->id)
            ->where('role.name', '=', 'appointments_provider')
            ->select(
                'member_user_role.id as provider_id',
                'member_user_role.role_id',
                'member_user_role.user_id',
                'user.first_name',
                'user.last_name',
                'user.email',
                'user.phone_number')
            ->get();
    }

    public function page(){

        return $this->hasOne(MemberPage::class);

    }

    protected function providers(){

        return $this->hasMany(Provider::class, 'member_id');

    }

    protected function services(){

        return $this->hasMany(Service::class, 'member_id');

    }

    public static function apiServiceList($memberId){
        $sql = 'select distinct c.name as service_category, c.id as service_category_id, s.id as service_id, s.name
        from delmax_crm.member m
         join providers p on p.member_id=m.id
         join services_providers sp on sp.provider_id=p.id
         join services s on s.id=sp.services_id and s.member_id=m.id
         join service_categories c on c.id=s.service_category_id and c.member_id=m.id
        where m.id=1559';
        return null;

    }

    public static function apiDatatables($projectId){

        $query = Member::join('project', 'member.project_id','=', 'project.id')
            ->leftJoin('member_information as core_activity', function ($join) {
                $join->on('core_activity.member_id','=','member.id')->where('core_activity.information_id', '=', 25); //Core activity
            })
            ->select(
                'member.id',
                'member.membership_id',
                'member.membership_type',
                'member.erp_partner_id',
                'member.name',
                'core_activity.value_text as core_activity',
                'member.approved_at',
                'member.rejected_at',               
                'member.email',
                'member.phone_number',
                'member.city_name',
                'member.postal_code',
                'member.address',
                'member.country_name',
                'member.longitude',
                'member.latitude'
            )
            ->where('member.project_id','=',(int)$projectId);

        $d = datatables()::of($query);


        $d->addColumn('actions', function ($model)  {
            return view('crm.projects.members.dt-actions', compact('model'));
        });

        return $d->make(true);
    }


    public static function apiDtPartners($projectId){

        $memberClass = 'Crm\\\\Models\\\\Partner';

        $query = Member::join('project', 'member.project_id','=', 'project.id')
            ->join('partner', 'partner.id', '=', 'member.membership_id')
            ->leftJoin('member_information as core_activity', function ($join) {
                $join->on('core_activity.member_id','=','member.id')->where('core_activity.information_id', '=', 25); //Core activity
            })
            ->select(
                'member.id',
                'member.membership_id',
                'partner.erp_company_id',
                'partner.erp_partner_id',
                'partner.description',
                'partner.description2',
                'core_activity.value_text as core_activity',
                'partner.tax_identification_number',
                'partner.email',
                'partner.phone',
                'partner.city_name',
                'partner.postal_code',
                'partner.address',
                'partner.country_name',
                'partner.longitude',
                'partner.latitude',
                'member.approved_at',
                'member.rejected_at'
            )
            ->where('member.project_id','=',(int)$projectId)
            ->whereRaw("member.membership_type='".$memberClass."'");

        $d = datatables()::of($query);

        $d->addColumn('actions', function ($model)  {
                return view('crm.projects.members.partners.dt-actions', compact('model'));
            });

        return $d->make(true);
    }

    public static function isInstaller($erp_partner_id) {

        if (is_null($erp_partner_id)) {
            return false;
        }

        $result = DB::connection('CRM')->select('
            select core_activity.value_integer as is_installer
            from partner p
                join member m on m.membership_id=p.id and m.project_id=2 and m.approved_at is not null and m.rejected_at is null
                left join member_information core_activity on core_activity.member_id=m.id and core_activity.information_id=25
            where p.erp_partner_id='. $erp_partner_id);
        
        return (empty($result)) ? false : ($result[0]->is_installer === 1);      
    }
}