<?php

namespace Delmax\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 7.4.2015
 * Time: 19:12
 */


class DimensionDescriptionTemplate extends Model{

    protected $connection='delmax_catalog';

    protected $table='description_dimension_template';


    /**
     * Dimension description template
     * @return mixed
     */
    public static function tyresTemplateArray(){

    	// DEBUG:
        // Cache::forget('description_dimension_template');
        $value = Cache::remember('description_dimension_template', 1440, function()
        {
            return self::select('description_dimension_template.dimension_id','dimension.description')

            	->join('dimension', 'dimension.dimension_id','=','description_dimension_template.dimension_id')

            	->whereNull('description_dimension_template.is_deleted')

            	->where('description_dimension_template.description_id','1679')

            	->orderBy('description_dimension_template.order_index')

            	->get();
        });

        return $value;

    }

}
