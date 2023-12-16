<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 30.9.2016
 * Time: 17:59
 */

namespace App\Http\Controllers;

use Delmax\Webapp\Models\MerchantApi;

class MerchantApiController extends DmxBaseController {


    /**
     * @var MerchantApi
     */
    private $merchantApi;
    /**
     * @var Response
     */


    public function __construct(MerchantApi $merchantApi){

        parent::__construct();

        $this->merchantApi = $merchantApi;

    }

    public function apiHealth($merchantId){

        $this->merchantApi = MerchantApi::where(['merchant_id'=>$merchantId])->first();

        $health = $this->merchantApi->getHealth();

        return  $health;
    }

}