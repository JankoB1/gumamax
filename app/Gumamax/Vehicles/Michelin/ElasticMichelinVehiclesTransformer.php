<?php namespace Gumamax\Vehicles\Michelin;

use Delmax\Transformers\Transformer;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.3.2015
 * Time: 22:14
 */


class ElasticMichelinVehiclesTransformer extends Transformer {
    /**
     * @param $vehicle
     * @return array
     */
    public function transform($vehicle){
        return [
            'brand'=>$vehicle['_source']['brand'],
            'range'=>$vehicle['_source']['range'],
            'model'=> $vehicle['_source']['model'],
            'engine'=> $vehicle['_source']['engine'],
            'years'=>$vehicle['_source']['production'],
            'dimensions'=>$vehicle['_source']['dimensions'],
        ];
    }
}