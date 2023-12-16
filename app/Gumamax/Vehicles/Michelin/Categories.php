<?php namespace app\Gumamax\Vehicles\Michelin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 24.3.2015
 * Time: 14:37
 */


class Categories extends Model{

    protected $connection='michelin';

    protected $table = 'michelin_category';

    protected $primaryKey = 'category_id';

    public static function findByNameOrFail($name, $columns = ['*'])
     {
        if ( ! is_null($category = static::whereName($name)->first($columns))) {
            return $category;
        }

        throw new ModelNotFoundException;
    }
}