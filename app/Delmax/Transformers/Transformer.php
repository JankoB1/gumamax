<?php namespace Delmax\Transformers;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 8/6/14
 * Time: 11:10 PM
 */


/**
 * Class Transformer
 * @package Delmax\Transformers
 */
/**
 * Class Transformer
 * @package Delmax\Transformers
 */
abstract class Transformer {

    /**
     * @param array $items
     * @return array
     */
    public function transformCollection(array $items){
        return array_map([$this, 'transform'], $items);
    }

    /**
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);
} 