<?php namespace Delmax\User\Activity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Datatables\Datatables;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 5.4.2015
 * Time: 9:06
 */


class UserActivity extends Model{

    use SoftDeletes;

    protected $connection = 'ApiDB';

    protected $table = 'user_activity';

    protected $primaryKey = 'id';

    protected $fillable = ['app_id', 'description', 'user_id', 'client_ip', 'payload'];

    public static function log($app_id, $description, Array $payload=[]){

        return new static([
            'app_id'        => $app_id,
            'description'   => $description,
            'client_ip'     => getIpAddress(),
            'payload'       => (count($payload)>0)?json_encode($payload):null
        ]);

    }

    /**
     * Logging gumamax user activity
     * @param $description
     * @param array|null $payload
     * @return static
     */
    public static function logGmxActivity($description, Array $payload=[]){

        return self::log(2, $description, $payload);

    }

    public static function logCdkActivity($description, Array $payload=[]){

        return self::log(2, $description, $payload);

    }

    public static function logGmxAnonymously($description, Array $payload=[]){

        UserActivity::create([
            'app_id'        => 2,
            'description'   => $description,
            'client_ip'     => getIpAddress(),
            'payload'       => (count($payload)>0)?json_encode($payload):null]);

    }

    public static function apiDatatablesGmx($userId, $withPayload){
        $fields = ['description', 'created_at'];
        if ($withPayload){
            $fields[] = 'payload';
        }

        $query = UserActivity::where('user_activity.app_id',2)
            ->where('user_activity.user_id',$userId)
            ->select($fields)
            ->orderBy('created_at', 'desc');

        if ($query){

            $d = Datatables::of($query);

            return $d->make(true);
        }

    }
}