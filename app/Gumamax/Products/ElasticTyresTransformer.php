<?php namespace Gumamax\Products;
    /**
     * Created by PhpStorm.
     * User: nikola
     * Date: 11.9.14.
     * Time: 09.34
     */
use Delmax\Transformers\Transformer;

/**
 * Class ProductTransformers
 * @package Delmax\Transformers
 */
class ElasticTyresTransformer extends Transformer {


    public function __construct(){


    }

    /**
     * @param $product
     * @return array
     */
    public function transform($product){
        return [
            'company_id'=>$product['_source']['company_id'],
            'merchant_id'=>$product['_source']['merchant_id'],
            'product_id'=>$product['_source']['product_id'],
            'manufacturer_id'=>$product['_source']['manufacturer_id'],
            'manufacturer'=>$product['_source']['manufacturer'],
            'cat_no'=> iconv('Windows-1250','UTF-8', $product['_source']['cat_no']),
            'description'=> iconv('Windows-1250','UTF-8', $product['_source']['description']),
            'description_id'=> $product['_source']['description_id'],
            'additional_description'=> iconv('Windows-1250','UTF-8', $product['_source']['additional_description']),
            'uom_id'=> $product['_source']['uom_id'],
            'packing'=> $product['_source']['packing'],
            'dmx_primary_type'=> $product['_source']['dmx_primary_type'],
            'ean'=> $product['_source']['ean'],
            'cross_ref'=> $product['_source']['cross_ref'],
            'note'=> $product['_source']['note'],
            'season'=>$product['_source']['season'],
            'vehicle_category'=>$product['_source']['vehicle_category'],
            'year_of_production'=>$product['_source']['year_of_production'],
            'diameter'=>$product['_source']['diameter'],
            'country_of_origin'=>$product['_source']['country_of_origin'],
            'thumbnail_image_url_54x50'=>$product['_source']['thumbnail_url_54x50'],
            'thumbnail_image_url_80x60'=>$product['_source']['thumbnail_url_80x60'],
            'thumbnail_image_url_120x90'=>$product['_source']['thumbnail_url_120x90'],
            'thumbnail_url_40'=>$product['_source']['thumbnail_url_40'],
            'thumbnail_url_110'=>$product['_source']['thumbnail_url_110'],
            'thumbnail_url_118'=>$product['_source']['thumbnail_url_118'],
            'thumbnail_url_140x140'=>$product['_source']['thumbnail_url_140x140'],
            'image_url'=>$product['_source']['image_url'],
            'min_order_qty'=>$product['_source']['min_order_qty'],
            'purchase_on_demand'=>$product['_source']['purchase_on_demand'],
            'max_order_qty'=>$product['_source']['max_order_qty'],
            'stock_status'=>$product['_source']['stock_status'],
            'price_with_tax'=>$product['_source']['price_with_tax'],
            'price_without_tax'=>$product['_source']['price_without_tax'],
            'images'=> isset($product['_source']['images']) ? $product['_source']['images'] : [],
            'dimensions'=>$product['_source']['dimensions'],
            'action_price'=>$product['_source']['action_price'],
            'list_price'=>$product['_source']['list_price'],
            'super_price'=>$product['_source']['super_price'],
            'discount'=>$product['_source']['discount'],
            'stock_status_qty'=>$product['_source']['stock_status_qty'],
            'rating'=>$product['_source']['rating'],
            'product_weight'=>$product['_source']['product_weight'],
            'tax_id'=>$product['_source']['tax_id'],
            'tax_rate'=>$product['_source']['tax_rate'],
            'eu_badge' => $this->transformEuBadge($product['_source']['dimensions'])
        ];
    }

    public function transformEuBadge($dimensions){

        $euBadge['consumption']='';
        $euBadge['grip']='';
        $euBadge['noise']='';
        $euBadge['noise_db']='';

        foreach ($dimensions as $dimension) {
            if ($dimension['dimension_id'] == 16)
                $euBadge['consumption'] = $dimension['value_text'];
            elseif ($dimension['dimension_id'] == 17)
                $euBadge['grip'] = $dimension['value_text'];
            elseif ($dimension['dimension_id'] == 18)
                $euBadge['noise'] = $dimension['value_text'];
            elseif ($dimension['dimension_id'] == 19)
                $euBadge['noise_db'] = $dimension['value_text'];
        }

        return $euBadge;
    }
}
