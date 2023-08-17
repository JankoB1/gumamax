<?php namespace App;


use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    const qa_moderator = 3;
    const gmx_user = 8;

    protected $connection = 'FW';

    /**
     *     * @return mixed
     */
    public function qaModerators(){

        return $this->find(Role::qa_moderator)->users();

    }

    public static function gmxUser(){

        return Role::where('name', 'gmx_user')->first();

    }

    public static function gmxUsers(){

        $role = self::find(self::gmx_user);

        return $role->users;

    }

}