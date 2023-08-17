<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:16
 */

namespace Crm\Models;


use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class Project extends Model
{
    protected $connection = 'CRM';

    protected $table = 'project';

    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'privacy_id',
        'company_id',
        'name',
        'short_description',
        'note',
        'star_date',
        'end_date',
        'domain',
        'members_count',
        'order_index',
        'icon'];

    public function partners(){

        return $this->morphToMany(Partner::class, 'membership');

    }

    public function members(){

        return $this->hasMany(Member::class, 'project_id');

    }

    public function privacy(){

        return $this->hasOne(Privacy::class);

    }

    public function informationTemplate(){

        return $this->hasOne(ProjectInformationTemplate::class, 'project_id');

    }

    public function memberUsersRoles(){

        return $this->belongsToMany(Role::class, 'delmax_crm.project_member_user_roles', 'project_id', 'role_id')->orderBy('order_index');

    }

    public function updateMembersCounter(){

        $count = $this->members()->count();

        $this->members_count = $count;

        return $this->save();
    }


    public static function apiDatatables(){

        $query = Project::select('id', 'parent_id', 'name', 'short_description', 'start_date', 'end_date');

        if($query){

            $d = Datatables::of($query);

            $d->addColumn('actions', function ($model){
                return '-';
            });

            return $d->make(true);
        }

    }

    public static function apiItems(){

        $query = Project::select(
            'id',
            'parent_id',
            'name',
            'note',
            'short_description',
            'start_date',
            'end_date',
            'domain',
            'icon',
            'members_count',
            DB::raw('(select count(*) from project cc where cc.deleted_at is null and cc.parent_id=project.id) as child_count')
        );

        return $query->get();

    }

}