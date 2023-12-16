<?php namespace Delmax\Webapp;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 27.9.2015
 * Time: 21:23
 */


class Subdomain
{
    public function create($member_id, $title, $name, $erp_company_id, $erp_partner_id){

        return session()->put('subdomain', [
            'member_id' => $member_id,
            'title'     => $title,
            'name'      => $name,
            'erp_company_id'      => $erp_company_id,
            'erp_partner_id'      => $erp_partner_id,
        ]);
    }

    public function inUse(){
        $subdomain = session('subdomain','');
        return (is_array($subdomain));
    }

    public function getErpPartnerId(){
        $subdomain = session('subdomain','');
        if (is_array($subdomain)){
            return $subdomain['erp_partner_id'];
        }
        return '';
    }

    public function getErpCompanyId(){
        $subdomain = session('subdomain','');
        if (is_array($subdomain)){
            return $subdomain['erp_company_id'];
        }
        return '';
    }

    public function getTitle(){
        $subdomain = session('subdomain','');
        if (is_array($subdomain)){
            return $subdomain['title'];
        }
        return '';
    }

    public function getName(){
        $subdomain = session('subdomain','');
        if (is_array($subdomain)){
            return $subdomain['name'];
        }
        return '';
    }

    public function getMemberId(){
        $subdomain = session('subdomain','');
        if (is_array($subdomain)){
            return $subdomain['member_id'];
        }
        return '';
    }
}
