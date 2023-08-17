<?php
namespace Delmax\Webapp\Facades;

use Illuminate\Support\Facades\Facade;

class DmxWebApp extends Facade {
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'dmxwebapp'; }
}