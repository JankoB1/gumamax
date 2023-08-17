<?php namespace Gumamax\Partners;

use Crm\Models\MemberPriceList;
use Delmax\Cart\Models\Cart;
use Delmax\Partners\PartnerPriceList;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2015
 * Time: 6:53
 */


class CartInstallationCost extends Model
{

    protected $connection = 'delmax_gumamax';

    protected $table='cart_installation_cost';

    protected $primaryKey='cart_id';

    protected $fillable = ['cart_id', 'alu', 'cel'];


    public function cart(){

        return $this->belongsTo(Cart::class);
    }

    public static function calculate($items, $memberId){

        $clSummary = 0;
        $alSummary = 0;

        if (!((is_null($memberId)||($memberId=='')||(is_null($items))))) {
            foreach ($items as $item) {
                $qty = $item['qty'];
                $diameter = $item['diameter'] ? $item['diameter'] : 0;
                $vehicle_category = $item['vehicle_category']?$item['vehicle_category']:'';

                $partnerPrices = MemberPriceList::tyreServicePrices($memberId, $vehicle_category, $diameter);
                $clSummary += $partnerPrices['price']['cl'] * $qty;
                $alSummary += $partnerPrices['price']['al'] * $qty;
            }
        }

        $costs = [
            "cel" => $clSummary,
            "alu" => $alSummary
        ];

        return $costs;
    }

}