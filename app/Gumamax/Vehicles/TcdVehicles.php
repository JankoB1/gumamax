<?php

namespace Gumamax\Vehicles;

use Delmax\Webapp\RestClient;

class TcdVehicles {

    private $restClient;

    function __construct(){
        $this->restClient = new RestClient(
            config('tcdvehicles.url'), 
            '', 
            '', 
            '',
            config('tcdvehicles.token'));
    }

    public function manufacturer($year=0){
        return $this->restClient->getCall('/manufacturers', 
            "pc=1&sort[0][key]=fav_pc&sort[0][direction]=desc&sort[1][key]=description&sort[1][direction]=asc");
    }

    public function model($year=0, $manufacturerId=0){
        return $this->restClient->getCall('/models', 
            "manufacturer_id=$manufacturerId&pc=1&year=$year&sort[0][key]=sort_key&sort[0][direction]=asc");
    }

    public function type($year=0, $manufacturerId=0, $modelId=0){
        return $this->restClient->getCall('/pctypes', "model_id=$modelId&year=$year");
    }


}