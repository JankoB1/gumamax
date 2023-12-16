<?php namespace Gumamax\Partners;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.9.2016
 * Time: 3:08
 */

use Illuminate\Database\Eloquent\Model;


class MembershipRequest extends Model {

    protected $connection = 'delmax_gumamax';

    protected $table = 'membership_request';

    protected $fillable = [
        'name',
        'department',
        'tax_identification_number',
        'first_name',
        'last_name',
        'city_id',
        'address',
        'phone',
        'email',
        'web_address',
        'longitude',
        'latitude',
        'is_installer',
        'processed_by_user_id',
        'approved_at',
        'rejected_at'
    ];


    public function getIsInstallerAttribute($value)
    {
        if ($value == 0) {
            return 'Prodavnica';
        } else {
            return 'Servis';
        }
    }

}