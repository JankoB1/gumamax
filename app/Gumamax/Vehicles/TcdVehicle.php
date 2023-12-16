<?php namespace Gumamax\Vehicles;
/**
 * Created by JetBrains PhpStorm.
 * User: nikola
 * Date: 9/21/13
 * Time: 10:47 PM
 */

use Delmax\Webapp\RestClient;


class TcdVehicle {

    private $restClient;

    function __construct(){
        $this->restClient = new RestClient(
            config('delmaxapi.url'),
            config('delmaxapi.username'),
            config('delmaxapi.password'));
    }

    public function manufacturer($year=0){
        return $this->restClient->getCall('/manufacturer', "year=$year");
    }

    public function model($year=0, $manufacturerId=0){
        return $this->restClient->getCall('/model', "year=$year&mfaid=$manufacturerId");
    }

    public function type($year=0, $manufacturerId=0, $modelId=0){
        return $this->restClient->getCall('/type', "year=$year&mfaid=$manufacturerId&modelid=$modelId");
    }


}
