<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.10.2016
 * Time: 19:41
 */

namespace Delmax\Partners\Interfaces;


interface PartnerRepositoryInterface
{
    public function findById($id);

    public function findBySubdomain($subdomain);

    public function search($query, $order='', $perPage=0, $page=-1);

    public function details($id);

    public function workingHours($id);

    public function paymentMethods($id);

    public function priceList($id);

    public function gallery($id);

    public function logo($id);

    public function cover($id);

}