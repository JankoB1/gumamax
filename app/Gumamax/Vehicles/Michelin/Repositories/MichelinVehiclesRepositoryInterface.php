<?php namespace Gumamax\Vehicles\Michelin\Repositories;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.3.2015
 * Time: 20:53
 */


interface MichelinVehiclesRepositoryInterface {


    public function getBrands();

    public function getRanges($brand);

    public function getModels($brand);

    public function getEngines($brand, $model);

    public function getYears($brand, $model, $engine);

    public function getDimensions($brand, $model, $engine, $years);

}