<?php

namespace App\Delmax\Products;

use Illuminate\Support\Facades\DB;

class ProductDimension {

    public static function getProductDimension($product_id, $description_id=null){
        $result = array();
        if (isset($product_id) && ($product_id!='')){
            if (isset($description_id))
                $result = self::getDimensionWithTemplate($product_id, $description_id);
            else {
                $sql = "
                SELECT d.dimension_id, d.description, coalesce(pd.value_text,'-') as value_text
                FROM product_dimension pd
                  LEFT JOIN dimension d on d.dimension_id = pd.dimension_id
                where pd.product_id = {$product_id} ";
                $result = DB::select($sql);
            }
        }
        return $result;
    }

    public static function getProductDimensionValue($product_id, $dimension_id){
        $sql = "SELECT pd.value_text
                FROM product_dimension pd
                WHERE pd.product_id = {$product_id} AND pd.dimension_id = {$dimension_id}";
        return DB::select($sql)[0];
    }

    public static function getDimensionWithTemplate($product_id, $description_id){
        $result = array();
        if ($product_id != ''){
            $sql = " SELECT
                        ddt.description_id,
                        ddt.dimension_id,
                        ddt.order_index,
                        ddt.is_deleted,
                        coalesce(pd.value_text,'-') as value_text,
                        d.description
                    FROM description_dimension_template ddt
                    LEFT OUTER join product_dimension pd on
                        ddt.dimension_id=pd.dimension_id and
                        pd.product_id = {$product_id} and
                        ddt.description_id = {$description_id}
                    LEFT OUTER JOIN dimension d on d.dimension_id = ddt.dimension_id
                    ORDER BY ddt.order_index";
            $result = DB::select($sql);
        }
        return $result;
    }

    public static function sync($data){
        $i=0;
        foreach($data as $rec){
            $p = DB::table('product_dimension')->where('product_id',$rec->PRODUCT_ID)->where('DIMENSION_ID',$rec->DIMENSION_ID)->first();

            if (is_null($p)){
                DB::table('product_dimension')->insert(array(
                    'product_id'=>$rec->PRODUCT_ID,
                    'dimension_id'=>$rec->DIMENSION_ID,
                    'value_num'=>$rec->VALUE_NUM,
                    'value_text'=>$rec->VALUE_TEXT
                ));
                $i++;
            } else {
                DB::table('product_dimension')
                    ->where('product_id',$rec->PRODUCT_ID)
                    ->where('dimension_id',$rec->DIMENSION_ID)
                    ->update(array(
                        'value_num'=>$rec->VALUE_NUM,
                        'value_text'=>$rec->VALUE_TEXT));
                $i++;
            }
        }
        return $i;
    }
}
