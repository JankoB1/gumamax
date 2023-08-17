<?php namespace Gumamax\Vehicles\Michelin\Repositories;


/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 14:25
 */


class VehiclesRepository implements MichelinVehiclesRepositoryInterface {


    public function getBrands()
    {
        // TODO: Implement getBrands() method.
    }

    public function getRanges($brand)
    {
        // TODO: Implement getRanges() method.
    }

    public function getModels($brand, $range)
    {
        // TODO: Implement getModels() method.
    }

    public function getEngines($brand, $range, $model)
    {
        // TODO: Implement getEngines() method.
    }

    public function getYears($brand, $range, $models, $engine)
    {
        // TODO: Implement getYears() method.
    }

    public function getDimensions($brand, $range, $models, $engine, $years)
    {
        // TODO: Implement getDimensions() method.
    }

}