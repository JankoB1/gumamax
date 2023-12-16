<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 3.10.2016
 * Time: 3:27
 */

namespace Delmax\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactFormMessage extends Model
{
    use SoftDeletes;

    protected $connection = 'ApiDB';

    protected $table = 'contact_form_message';

    protected $primaryKey = 'id';

    protected $fillable = ['app_id', 'user_id', 'name', 'email', 'message', 'from_ip', 'agent_id', 'answer', 'answered_at'];


    public static function apiCount($status){
        if ($status=='opened') {
            return ContactFormMessage::whereNull('answered_at')->count();
        } else {
            return ContactFormMessage::whereNotNull('answered_at')->count();
        }
    }

}