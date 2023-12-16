<?php 

namespace Delmax\Webapp\Site;

use Xinax\LaravelGettext\Facades\LaravelGettext;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 22.11.2014
 * Time: 22:46
 */
class Language {

    public $locales=[
        'en_US'=>[
            'title'=>'English',
            'short'=>'EN',
            'icon'=>'flag flag-gb',
            'locale'=>'en_US'
        ],
        'sr_RS'=>[
            'title'=>'Srpski',
            'short'=>'SRB',
            'icon'=>'flag flag-rs',
            'locale'=>'sr_RS'
        ]
    ];

    public $locale;
    public $active;

    public function __construct() {

        $locale = session('locale', 'en_US');
        $this->setLocale($locale);
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {

        if (!array_key_exists($locale, $this->locales)) {

            $locale = 'en_US';

        }

        $this->locale = $locale;

        session()->put('locale', $locale);

        LaravelGettext::setLocale($locale);

        $this->active = $this->locales[$this->locale];
    }
}