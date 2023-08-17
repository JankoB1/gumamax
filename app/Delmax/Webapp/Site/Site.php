<?php namespace Delmax\Webapp\Site;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 2.3.2015
 * Time: 20:34
 */


class Site {

    public $title;
    public $description;
    public $author;
    public $language;
    public $vehicleCategory;

    public function __construct(){
        $this->title = 'Prodaja guma |  gumamax.com';
        $this->description ='Gume, Guma, Zimska, Zimske gume, Gume za auto, Tigar, Michelin, Kleber, Continental, autodelovi, delovi za auto, kvačilo, kaiš, filter, rezervni delovi';
        $this->author='marcha';
    }
}