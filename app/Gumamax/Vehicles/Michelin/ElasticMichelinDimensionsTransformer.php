<?php namespace Gumamax\Vehicles\Michelin;
use Delmax\Transformers\Transformer;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 28.3.2015
 * Time: 8:46
 */


class ElasticMichelinDimensionsTransformer extends Transformer {

    /**
     * @param $item
     * @return mixed
     */
    public function transform($item)
    {
        return [
             'dimensions'=> $item['_source']['dimensions']
            ];
    }
}