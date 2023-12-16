<?php namespace App\Gumamax\Users;

use DB;
use Session;

class Guest {

    public static function getGuestId(){
        $id = Session::get('guest_id');
        if (is_null($id)||($id==0)){
           $id=self::addGuest();
           Session::put('guest_id', $id);
        }
        return $id;
    }

    public static function addGuest(){
        return DB::table('guest')->insertGetId(
            array('ip_address' => getIpAddress()));
    }

    public static function setPreferredPartnerId($partner_id){
        $guest_id=(int)self::getGuestId();
        if ($guest_id>0){
        return DB::table('guest')
            ->where('guest_id','=', $guest_id)
            ->update(
            array('preferred_partner_id'=>(int)$partner_id,
                  'updated_at'=> date("Y-m-d H:i:s"),
                  'ip_address' => getIpAddress())
            );
        }
    }

    public static function setVehicleTypId($vehicle_typ_id){
        $regVehicle = UserVehicle::getInputVehicleData();
        $guest_id=(int)self::getGuestId();
        $r=DB::table('user_vehicle')
            ->where('guest_id','=',$guest_id)
            ->update(array(
                'typ_id'=>$vehicle_typ_id,
                'name'=>$regVehicle['vehicleName'],
                'vin'=>$regVehicle['vin'],
                'engine_code'=>$regVehicle['engineCode'],
                'updated_at'=>date("Y-m-d H:i:s")
            ));
        if ($r==0){
            $r = DB::table('user_vehicle')
                ->insert(array(
                    'guest_id'=>$guest_id,
                    'typ_id'=>$vehicle_typ_id,
                    'name'=>$regVehicle['vehicleName'],
                    'vin'=>$regVehicle['vin'],
                    'engine_code'=>$regVehicle['engineCode'],
            ));
        }
        return $r;
    }
}