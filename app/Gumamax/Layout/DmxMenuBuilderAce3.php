<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 22:10
 */

namespace Gumamax\Layout;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;

class DmxMenuBuilderAce3
{

    private $dbItems;

    private $rootId;

    public $breadcrumbs=[];

    public function build($rootId = null)
    {
        $this->rootId = $rootId;

        $this->dbItems = Menu::getItems();

        $this->preProcessItems();

        $menuItemsArray = $this->dbItems->toArray();

        $menuItemsArray = buildTreeArray($menuItemsArray , $rootId);

        usort($menuItemsArray, function($a, $b) {
            return $a['order_index'] - $b['order_index'];
        });

        return '<ul class="nav nav-list">'.$this->decorateTree($menuItemsArray).'</ul>';

    }

    private function preProcessItems(){

        $activeItemId = request()->input('menu_id', 0);

        foreach ($this->dbItems as &$item){

            $item->active=false;

            $item->url=$this->getItemUrl($item);

        }

        $this->setActiveParent($this->dbItems, $activeItemId);
    }

    private function setActiveParent(&$items, $itemId){

        foreach ($items as &$item){
            if (($item->id==$itemId)&&(!$item->active)){
                $item->active = true;

                if ($item->id!==$this->rootId){
                    $this->breadcrumbs[] = $item;
                }

                if ($item->parent_id){
                    $this->setActiveParent($items, $item->parent_id);
                    break;
                }
            }
        }
    }


    private function getItemUrl($item){

        $dmxUrlParams = ['menu_id'=>$item->id];

        if (isset($item->url)) {

            return url($item->url, $dmxUrlParams);
        }

        if (!isEmpty($item->route_name)) {

            $routeExists = Route::has($item->route_name);

            if ($routeExists) {

                $routeParams = [];

                if (isset($item->params)){

                    $paramsArray = explode('&', $item->params);

                    foreach($paramsArray as $param){

                        $param1 = explode('=',$param);

                        $routeParams[$param1[0]]=$param1[1];
                    }
                }

                $params = array_merge($dmxUrlParams, $routeParams);

                return route($item->route_name, $params);
            }
        }

        return '#';
    }

    public function decorateTree(array &$tree){
        $html = '';

        $itemTemplate = '<li%s>%s</li>';
        $rootItemTemplate = '<li%s>%s <ul class="submenu">%s</ul></li>';

        foreach ($tree as &$item ) {
            $isActive         = $item["active"];
            $liClass          = ($isActive)             ? ' class="active"' : '';
            $parentLiClass    = ($isActive)             ? ' class="active open"' : '';
            $url        = $item['url'];
            $url_target = !isEmpty($item["url_target"]) ? 'target="'.$item["url_target"].'"' : "";
            $icon_badge = isset($item["icon_badge"])    ? '<em>'.$item["icon_badge"].'</em>' : '';
            $icon       = isset($item["icon"])          ? '<i class="menu-icon fa '.$item["icon"].'">'.$icon_badge.'</i>' : "";
            $nav_title  = $item['title'];
            $label_htm  = isset($item["label_htm"]) ? $item["label_htm"] : "";
            $title = '<span class="menu-text">'.$nav_title.'</span>';

            if (isset($item['children'])){
                $itemContent = '<a href="'.$url.'" '.$url_target.' title="'.$nav_title.'" class="dropdown-toggle">'.$icon.$title.$label_htm.'<b class="arrow fa fa-angle-down"></b></a>';
                $html .=   "\r\n\t".sprintf($rootItemTemplate, $parentLiClass, $itemContent, $this->decorateTree($item['children']));
            } else {
                $itemContent = '<a href="'.$url.'" '.$url_target.' title="'.$nav_title.'">'.$icon.$title.$label_htm.'</a><b class="arrow"></b>';
                $html .=  "\r\n\t". sprintf($itemTemplate, $liClass, $itemContent. "\r\n\t") ;
            }
        }

        return $html;
    }


    public function getBreadcrumbs(){

        $i=0;

        $count = count($this->breadcrumbs);

        $this->breadcrumbs = array_reverse($this->breadcrumbs);

        print('<ul class="breadcrumb">');

        foreach ($this->breadcrumbs as $crumb){
            $i++;
            if ($i<$count){
                $itemContent = '<a href="'.$crumb->url.'" title="'.$crumb->title.'">'.$crumb->title.'</a>';
                print('<li>'.$itemContent.'</li>');
            } else {
                print('<li class="active">'.$crumb->title.'</li>');
            }

        }
        print('</ul>');
    }

}