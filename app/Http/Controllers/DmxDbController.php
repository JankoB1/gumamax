<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class DmxDbController extends Controller
{
    protected $cyr  = array('а','б','в','г','д','ђ','е','ж','з','и','ј','к','л','љ','м',
        'н','њ','о','п','р','с','т','ћ','у','ф','х','ц','ч','џ','ш',
        'А','Б','В','Г','Д','Ђ','Е','Ж','З','И','Ј','К','Л','Љ','М',
        'Н','Њ','О','П','Р','С','Т','Ћ','У','Ф','Х','Ц','Ч','Џ','Ш');

    protected $lat  = array('a','b','v','g','d','đ','e','ž','z','i','j','k','l','lj','m',
        'n','nj','o','p','r','s','t','ć','u','f','h','c','č','dž','š',
        'A','B','V','G','D','Đ','E','Ž','Z','I','J','K','L','Lj','M',
        'N','Nj','O','P','R','S','T','Ć','U','F','H','C','Č','Dž','Š');

    protected $dmx_obj_id;

    protected $from_dmx_obj_id;

    protected $menu_id;

    protected $settings;

    public function __construct(){

        $this->dmx_obj_id   = Input::get('dmx_obj_id');

        $this->menu_id      = Input::get('menu_id');

        if (isset($this->menu_id)) {
            session()->put('menu-item', $this->menu_id);
        }

        if (isset($this->dmx_obj_id)) {

            $this->from_dmx_obj_id = Input::get('from_dmx_obj_id');

            $this->settings = dmxapp()->settings($this->dmx_obj_id);

            $this->settings->dmx_obj_id = $this->dmx_obj_id;

            $this->settings->dmx_from_obj_id = $this->from_dmx_obj_id;
        }
    }

}
