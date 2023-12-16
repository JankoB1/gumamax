<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.8.2015
 * Time: 9:39
 */

namespace Delmax\Webapp\Layout;


use Gumamax\Layout\Menu;
use Illuminate\Support\Facades\Route;

class DmxMenuBuilder
{
    /**
     * Menu items from database
     */
    private $dbItems = [];


    /**
     * HTML contents
     */
    private $html  = [];



    public function createTreeArray($rootId=null){

        $this->dbItems = Menu::getItems();

        $this->preProcessItems();

        $menuItemsArray = $this->dbItems->toArray();

        $menuItemsArray = buildTreeArray($menuItemsArray , $rootId);

        usort($menuItemsArray, function($a, $b) {
            return $a['order_index'] - $b['order_index'];
        });

        return $menuItemsArray;
    }
    /**
     * Build the HTML for the menu
     * @param int $rootId
     * @return string
     */
    public function build($rootId = null)
    {
        $this->html  = [];

        $menuItemsArray = $this->createTreeArray($rootId);

        return '<ul>'.$this->decorateTree($menuItemsArray).'</ul>';

    }

    public function decorateTree(array &$tree){
        $html = '';

        $itemTemplate = '<li%s>%s</li>';
        $rootItemTemplate = '<li%s>%s <ul>%s</ul></li>';

        foreach ($tree as &$item ) {
            $isActive   = isset($item["active"]);
            $liClass    = ($isActive)                   ? ' class="active"' : '';
            $url        = $item['url'];
            $url_target = !isEmpty($item["url_target"]) ? 'target="'.$item["url_target"].'"' : "";
            $icon_badge = isset($item["icon_badge"])    ? '<em>'.$item["icon_badge"].'</em>' : '';
            $icon       = isset($item["icon"])          ? '<i class="fa fa-lg fa-fw '.$item["icon"].'">'.$icon_badge.'</i>' : "";
            $nav_title  = $item['title'];
            $label_htm  = isset($item["label_htm"]) ? $item["label_htm"] : "";
            $title = '<span class="menu-item-parent">'.$nav_title.'</span>';
            $itemContent = '<a href="'.$url.'" '.$url_target.' title="'.$nav_title.'">'.$icon.$title.$label_htm.'</a>';

            if (isset($item['children'])){

                $html .=   "\r\n\t".sprintf($rootItemTemplate, $liClass, $itemContent, $this->decorateTree($item['children']));
            } else
                $html .=  "\r\n\t". sprintf($itemTemplate, $liClass, $itemContent. "\r\n\t") ;
        }

        return $html;
    }


    private function getItemUrl($item){

        $dmxUrlParams = ['dmx_obj_id'=>$item->dmx_id, 'menu_id'=>$item->id];

        if (isset($item->url)) {
            return url($item->url, $dmxUrlParams);
        }

        if (!isEmpty($item->route_name)) {

            $routeExists = Route::has($item->route_name);

            if ($routeExists) {
                return route($item->route_name, $dmxUrlParams);
            }
        }

        return '#';
    }

    private function getItemUrlArr($item){

        $dmxUrlParams = ['dmx_obj_id'=>$item->dmx_id, 'menu_id'=>$item->id];

        if (isset($item->url)) {
            return url($item->url,$dmxUrlParams);
        }

        if (!isEmpty($item->route_name)) {

            $routeExists = Route::has($item->route_name);

            if ($routeExists) {
                return route($item->route_name, $dmxUrlParams);
            }
        }

        return '#';
    }

    private function preProcessItems(){

        $activeItem =  session('menu-item', 0);

        foreach ($this->dbItems as &$item){

            $item->active=null;

            if ($item->id==$activeItem){

                $item->active = true;

            }

            $item->url=$this->getItemUrl($item);

        }
    }

    private function preProcessItemsArr(){

        $activeItem =  session('menu-item',0);

        foreach ($this->dbItems as &$item){

            $item->active=null;

            if ($item->id==$activeItem){

                $item->active = true;

            }

            $item->url=$this->getItemUrlArr($item);

        }
    }

}