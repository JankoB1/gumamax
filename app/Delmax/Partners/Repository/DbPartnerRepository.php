<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.10.2016
 * Time: 19:42
 */

namespace Delmax\Partners\Repository;


use Delmax\Partners\Interfaces\PartnerRepositoryInterface;
use Delmax\Partners\Partner;

class DbPartnerRepository implements PartnerRepositoryInterface
{

    public function findById($id)
    {
        return Partner::find($id);
    }

    public function findBySubdomain($subdomain)
    {
        return Partner::where(['subdomain'=>$subdomain])->first();
    }

    public function search($query, $order='', $perPage = 0, $page = -1)
    {
        // TODO: Implement search() method.
    }

    public function details($id)
    {
        // TODO: Implement details() method.
    }

    public function workingHours($id)
    {
        // TODO: Implement workingHours() method.
    }

    public function paymentMethods($id)
    {
        // TODO: Implement paymentMethods() method.
    }

    public function priceList($id)
    {
        // TODO: Implement priceList() method.
    }

    public function gallery($id)
    {
        // TODO: Implement gallery() method.
    }

    public function logo($id)
    {
        // TODO: Implement logo() method.
    }

    public function cover($id)
    {
        // TODO: Implement cover() method.
    }
}