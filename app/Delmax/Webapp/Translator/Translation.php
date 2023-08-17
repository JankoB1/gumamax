<?php namespace Delmax\Webapp\Translator;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 13.8.2015
 * Time: 15:59
 */


class Translation extends Model
{
    protected $connection='ApiDB';
    protected $table='lng_translation';
    protected $primaryKey='';

    public function get($designationId){



    }

    public function language(){

        return $this->belongsTo(Language::class, 'locale');

    }
}