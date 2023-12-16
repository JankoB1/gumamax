<?php namespace Gumamax\Vehicles\Michelin;
use Delmax\Transformers\Transformer;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.3.2015
 * Time: 2:30
 */


class ElasticMichelinModelsTransformer extends Transformer {
    /**
     * @param $vehicle
     * @return array
     */
    public function transform($vehicle){
        return [
            'name' =>$vehicle['key'],
            'models'=> $vehicle['models']['buckets']
        ];
    }
}