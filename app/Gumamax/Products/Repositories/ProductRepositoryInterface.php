<?php

namespace Gumamax\Products\Repositories;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 19:30
 */


interface ProductRepositoryInterface {

    public function range($fromId, $toId);

    public function tyresSearch($query=[], $order='', $perPage=0, $page=-1);

    public function tyresReplacements($query=[], $order='', $perPage=0, $page=-1);

    public function tyresWidths($vehicleCategory);

    public function tyresRatios($vehicleCategory, $width);

    public function tyresDiameters($vehicleCategory, $width, $ratio);

    public function getTotal();

    public function productCount();

    /**
     * @param array $query array of product_id
     * @return mixed
     */
    public function getById($query=[]);

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    public function findFiltered();

}
