<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 12.10.2016
 * Time: 11:16
 */

namespace Crm\Models;


use Delmax\Attachments\Attachment;
use Delmax\Webapp\Models\City;
use Illuminate\Database\Eloquent\Model;
use Yajra\Datatables\Datatables;

class Partner extends Model
{
    protected $connection = 'CRM';

    protected $table = 'partner';

    protected $primaryKey = 'id';

    public function city(){

        return $this->hasOne(City::class, 'city_id', 'city_id');

    }

    public function attachments(){

        return $this->morphMany(Attachment::class, 'attachable');

    }

    public function membership(){

        return $this->morphMany(Partner::class, 'membership');

    }

    public function logo(){

        return $this->hasOne(PartnerLogo::class, 'id');

    }

    public function cover(){

        return $this->hasOne(PartnerCover::class, 'id');

    }

    public function paymentMethods(){

        return $this->belongsToMany(PaymentMethod::class, 'delmax_gumamax.partner_payment_method', 'erp_partner_id', 'payment_method_id');

    }

    public function openingHours(){

        return $this->hasMany(PartnerWorkingHour::class, 'partner_id', 'id');

    }

    public static function apiDatatables(){

        $query = Partner::select(['id',
            'erp_company_id',
            'erp_partner_id',
            'description',
            'description2',
            'address',
            'tax_identification_number',
            'city_name',
            'postal_code',
            'country_name',
            'phone',
            'email',
            'latitude',
            'longitude']);

        if ($query) {

            $d = datatables()::of($query);

            $d->addColumn('actions', function($model) {
                return view('crm.partners.dt-actions', compact('model'));
            });

            return $d->make(true);
        }
    }
}