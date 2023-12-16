<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 23:00
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Crm\Models\Company;
use Illuminate\Support\Facades\Cache;

class DmxBaseController extends ApiController
{

    protected $menu_id;
    protected $crmCompanyId;

    public function __construct(){

        $this->menu_id = request()->input('menu_id');
        $crmCompanyId = request()->input('crmCompanyId');

        if (isset($crmCompanyId)) {

            $company = Company::find($crmCompanyId);

            session()->put('crmCompanyId', $crmCompanyId);
            session()->put('crmCompanyName', $company->name);

        }

        $this->crmCompanyId = session('crmCompanyId');

        if (isset($this->menu_id)) {

            session()->put('menu-item', $this->menu_id);

        }
    }

    public function flushCache($cacheTags){
        if (is_array($cacheTags)){

            Cache::tags($cacheTags)->flush();

        } else
            Cache::tags([$cacheTags])->flush();
    }

}