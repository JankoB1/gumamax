<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:17
 */

namespace Crm\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class MemberInformation extends Model
{
    protected $connection = 'CRM';

    protected $table = 'member_information';

    protected $primaryKey = 'id';

    protected $fillable = ['member_id', 'information_id', 'value_text', 'value_decimal', 'value_integer', 'datatype'];


    public static function apiDtFrontend($member){

        $query = ProjectInformationTemplate::join('information', 'information.id','=', 'project_information_template.information_id')
            ->join('information_group', 'information_group.id', '=', 'project_information_template.information_group_id')
            ->leftJoin('member', function ($join) use ($member) {
                $join->on('member.project_id','=', 'project_information_template.project_id')
                    ->where('member.id', '=', $member->id);
            })
            ->leftJoin('member_information', function($join){
                $join->on('member_information.member_id','=', 'member.id')
                    ->on('member_information.information_id', '=', 'project_information_template.information_id');
            })
            ->where('project_information_template.privacy_id','=',2)
            ->where('member_information.value_text','<>','')
            ->whereNull('information.deleted_at')
            ->select(
                'member_information.id',
                'project_information_template.id as template_id',
                'project_information_template.lookup_json',
                'information.datatype',
                'information.edit_control',
                'information_group.title as information_group',
                'information.id as information_id',
                DB::raw("coalesce(information.lookup_json, project_information_template.lookup_json, '') as lookup_json"),
                'member.id as member_id',
                'information.label',
                'information.description',
                DB::raw("coalesce(member_information.value_text,'') as value"),
                'member_information.value_text',
                'member_information.value_integer',
                'member_information.value_decimal'
            )->where('project_information_template.project_id','=',$member->project_id)
            ->whereRaw("member.membership_type='".addslashes($member->membership_type)."'");

        return datatables()::of($query)->make(true);
    }

    public static function apiDatatables($member)
    {

        $query = ProjectInformationTemplate::join('information', 'information.id','=', 'project_information_template.information_id')
            ->join('information_group', 'information_group.id', '=', 'project_information_template.information_group_id')
            ->leftJoin('member', function ($join) use ($member) {
                $join->on('member.project_id','=', 'project_information_template.project_id')
                    ->where('member.id', '=', $member->id);
            })
            ->leftJoin('member_information', function($join){
                $join->on('member_information.member_id','=', 'member.id')
                    ->on('member_information.information_id', '=', 'project_information_template.information_id');
            })->select(
                'member_information.id',
                'project_information_template.id as template_id',
                'project_information_template.lookup_json',
                'information.datatype',
                'information.edit_control',
                'information_group.title as information_group',
                'information.id as information_id',
                DB::raw("coalesce(information.lookup_json, project_information_template.lookup_json, '') as lookup_json"),
                'member.id as member_id',
                'information.label',
                'information.description',
                DB::raw("coalesce(member_information.value_text,'') as value"),
                'member_information.value_text',
                'member_information.value_integer',
                'member_information.value_decimal'
            )->where('project_information_template.project_id','=',$member->project_id)
            ->whereRaw("member.membership_type='".addslashes($member->membership_type)."'");

        return datatables()::of($query)->rawColumns(['lookup_json'])->toJson();
    }
}