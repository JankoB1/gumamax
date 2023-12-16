<?php namespace Delmax\Webapp\Models;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 29.8.2015
 * Time: 21:44
 */

use Illuminate\Database\Eloquent\Model;

class DmxModel extends Model
{
    /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            self::setNullables($model);
        });
    }

    /**
     * Set empty fields to null
     * @param object $model
     */
    protected static function setNullables($model)
    {
        foreach($model->fillable as $field)
        {
            if(isset($model->{$field})&&($model->{$field}===''))
            {
                $model->{$field} = null;
            }
        }
    }

}