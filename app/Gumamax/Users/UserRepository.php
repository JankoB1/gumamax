<?php namespace Gumamax\Users;

use App\Role;
use Gumamax\Registration\RegisterUserJob;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 14.9.2015
 * Time: 12:27
 */


class UserRepository
{
    use DispatchesJobs;

    public function save(User $user){

        $user->save();

    }

    public function findOrRegister($data){

        $user = $this->findById($data['user_id']);

        if (!$user){

            $user = $this->dispatchFromArray(RegisterUserJob::class, $data);

        }

        return $user;

    }

    /**
     * Find a user by their id.
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function apiDatatables($role){
        $addressable_type='App\\User';

        $query = User::join('fw_001.rbac_user_role as user_role', 'user_role.user_id', '=', 'user.user_id')
            ->join('fw_001.rbac_role as role', function ($join) use($role){
                $join->on('role.id', '=', 'user_role.role_id');
            })->leftJoin('delmax_crm.address as address', function ($join) use($addressable_type) {
                $join->on('address.addressable_id', '=', 'user.user_id')
                    ->where('address.addressable_type', '=', $addressable_type)
                    ->where('address.address_type_id', '=', 5);
            })->where('role.name', '=', $role)

            ->select('user.user_id', 'user.first_name', 'user.last_name', 'user.email', 'user.phone_number', 'address.city_name');

        if ($query) {

            $d = datatables()::of($query);
            $d->addColumn('actions', function($model){
                return view('admin.user.dt-actions', compact('model'));
            });

            return $d->make(true);
        }

    }

    public function lookupQuery(Request $request){

        $q = $request->get('q');

        return User::select('user_id as id', DB::raw("coalesce(concat(first_name, ' ', last_name, ' ', email),'') as text"))
            ->where('email','like','%'.$q.'%')
            ->orWhere('first_name','like','%'.$q.'%')
            ->orWhere('last_name','like','%'.$q.'%')
            ->get();
    }
}
