<?php namespace Delmax\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 14.9.2015
 * Time: 11:42
 */


class UserCustomer extends Model
{
    protected $connection = 'ApiDB';

    protected $table='user_customer';

    protected $fillable = [ 'user_id', 'customer_type_id', 'company_name', 'tax_identification_number',
        'receive_newsletter', 'erp_partner_id'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id');

    }

    public function customerType(){

        return $this->hasOne(CustomerType::class, 'customer_type_id', 'customer_type_id');

    }

    public static function makeByValues($customer_type_id, $company_name, $tax_identification_number, $receive_newsletters, $erp_partner_id=null){

        return new static (compact('customer_type_id', 'company_name', 'tax_identification_number', 'receive_newsletters', 'erp_partner_id'));

    }

    public static function make(array $data){

        return new static ($data);

    }
}