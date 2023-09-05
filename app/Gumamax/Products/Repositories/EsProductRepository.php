<?php namespace Gumamax\Products\Repositories;

use Delmax\elastic\DelmaxElastic;
use Delmax\Products\Product;
use Gumamax\Products\ElasticTyresTransformer;

use function PHPUnit\Framework\isNull;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 19:55
 */

class EsProductRepository implements ProductRepositoryInterface {


    private $data;

    private $searchParams;

    public $transformer;

    private $must = [];

    private $mustNot = [];

    private $filter = [];

    private $elastic;

    public function __construct(){

        $this->elastic = new DelmaxElastic('gumamax', 'tyres_2016', 'es_id');

        $this->transformer = new ElasticTyresTransformer();

        $this->must = [
            ['match' => ['company_id' => '8000']],

            ['match' => ['merchant_id' => '8080']],

            ['match' => ['visible' => '1']]
        ];

        $this->mustNot = [
            ['match' => ['stock_status' => 0]]
        ];
    }

    public function range($fromId, $toId)
    {
        // TODO: Implement range() method.
    }

    public function tyresSearch($query = [], $order = '', $perPage = 0, $page = -1)
    {

        $this->searchParams['body']['from'] = $this->calculateFrom($page, $perPage);

        $this->searchParams['body']['size'] = $perPage;

        if ($this->elastic->queryString !== '') {

            if($query['vehicle_category'] != '') {
                $this->must[]= ['match' => ['vehicle_category' => $query['vehicle_category']]];
            }


            if($query['width'] != '') {
                $this->must[] = ['match' => ['width' => $query['width']]];
            }

            if($query['ratio'] != '') {
                $this->must[] = ['match' => ['ratio' => $query['ratio']]];
            }

            if($query['diameter'] != '') {
                $this->must[] = ['match' => ['diameter' => $query['diameter']]];
            }

            if ($query['manufacturer'] != '') {

                $this->must[] = ['match' => ['manufacturer.facet' => $query['manufacturer']]];

            }

            if ($query['season'] != '') {

                $this->must[] = ['match' => ['season.facet' => $query['season']]];

            }

            if ($query['speed_index'] != '') {

                $this->must[] = ['match' => ['speed_index.facet' => $query['speed_index']]];

            }

            $this->searchParams['body']['query']['bool']['must'] = $this->must;

            $this->searchParams['body']['query']['bool']['must_not'] = $this->mustNot;

            $this->searchParams['body']['aggregations']['manufacturers']['terms']['field'] = 'manufacturer.facet';

            $this->searchParams['body']['aggregations']['seasons']['terms']['field'] = 'season.facet';

            $this->searchParams['body']['aggregations']['speed_indexes']['terms']['field'] = 'speed_index.facet';

            $newOrder = $this->addSeasonOrder($order);

            $this->elastic->setOrder($this->searchParams, $newOrder);

            $this->data = $this->elastic->executeQuery($this->searchParams);

            return $this->data;
        }

        return null;
    }

    public function tyresReplacements($query = [], $order = '', $perPage = 0, $page = -1){

        $originalProduct = $this->findById($query['product_id']);

        if (isNull($originalProduct)) {

            return null;
        }

        $this->searchParams['body']['from']  = $this->calculateFrom($page, $perPage);
        $this->searchParams['body']['size']  = $perPage;

        $query['vehicle_category'] = $originalProduct['vehicle_category'];
        $query['season'] = $originalProduct['season'];
        $query['diameter'] = $originalProduct['diameter'];


        foreach($originalProduct['dimensions'] as $dimension){
            foreach($dimension as $key=>$value){
                if (($key=='dimension_id')&&($value==11)){
                    $query['width']=$dimension['value_text'];
                }else if (($key=='dimension_id')&&($value==12)){
                    $query['ratio']=$dimension['value_text'];
                }else if (($key=='dimension_id')&&($value==15)){
                    $query['speed_index']=$dimension['value_text'];
                }
            }
        }

        if ($this->elastic->queryString!=='') {

            if($query['product_id']!=''){
                $this->mustNot[]= ['match' => ['product_id' => $query['product_id']]];
            }

            if($query['requested_qty']!=''){

                $this->must[]= ['range' => ['stock_status_qty' => ['gte'=>$query['requested_qty']]]];
            }

            if($query['vehicle_category']!=''){
                $this->must[]= ['match' => ['vehicle_category' => $query['vehicle_category']]];
            }


            if($query['width']!=''){
                $this->must[]= ['match' => ['width' => $query['width']]];
            }

            if($query['ratio']!=''){
                $this->must[]= ['match' => ['ratio' => $query['ratio']]];
            }

            if($query['diameter']!=''){
                $this->must[]= ['match' => ['diameter' => $query['diameter']]];
            }

            if ($query['manufacturer'] !== '') {

                $this->must[] = ['match' => ['manufacturer.facet' => $query['manufacturer']]];

            }

            if ($query['season'] !== '') {

                $this->must[] = ['match' => ['season.facet' => $query['season']]];

            }

            if ($query['speed_index'] !== '') {

                $this->must[] = ['match' => ['speed_index.facet' => $query['speed_index']]];

            }

            if (count($this->filter)>0){

                $this->searchParams['body']['query']['bool']['filter'] = $this->filter;

            }

            $this->searchParams['body']['query']['bool']['must'] = $this->must;

            $this->searchParams['body']['query']['bool']['must_not'] = $this->mustNot;

            $this->elastic->setOrder($this->searchParams, $order);

            $this->data = $this->elastic->executeQuery($this->searchParams);

            return $this->data;
        }

        return null;

    }

    public function tyresWidths($vehicle_category)
    {
        $testString = str_replace(' ','',$vehicle_category);

        if ($testString!='') {

            $this->must[] = ['match' => ['vehicle_category' => $vehicle_category]];

            return $this->getBuckets('widths', 'width');
        }

        return null;
    }

    public function tyresRatios($vehicle_category, $width)
    {
        $testString = str_replace(' ','',$vehicle_category.$width);

        if ($testString!='') {

            $this->must[] = ['match' => ['vehicle_category' => $vehicle_category]];

            $this->must[] = ['match' => ['width' => $width]];

            return $this->getBuckets('ratios', 'ratio');
        }

        return null;

    }

    public function tyresDiameters($vehicle_category, $width, $ratio)
    {
        $testString = str_replace(' ','',$vehicle_category.$width.$ratio);

        if ($testString!='') {

            $this->must[] = ['match' => ['vehicle_category' => $vehicle_category]];

            $this->must[] = ['match' => ['width' => $width]];

            $this->must[] = ['match' => ['ratio' => $ratio]];

            return $this->getBuckets('diameters', 'diameter');
        }

        return null;
    }

    public function getTotal()
    {
        return $this->data['hits']['total'];
    }

    /**
     * @param array $query array of product_id
     * @return mixed
     */
    public function getById($query = [])
    {
        $this->searchParams['body']['from']  = 0;

        $this->searchParams['body']['size']  = 1;
        $result = null;

        foreach ($query as $key=>$value) {

            $product_id = $this->escapeSlash($value);


            if ($product_id != '') {

                $newMust = ['match' => ['product_id' => $product_id]];

                $this->searchParams['body']['query']['bool']['must'] = $this->must;
                $this->searchParams['body']['query']['bool']['must'][] = $newMust;

                $this->searchParams['body']['query']['bool']['must_not'] = $this->mustNot;

                $this->data = $this->elastic->executeQuery($this->searchParams);

                $items = $this->data['hits']['hits'];

                $data = $this->transformer->transformCollection($items);

                if (!empty($data)) {
                    $result[] = $data[0];
                }
            }
        }

        if (is_array($result)){
            return $result;
        }

        return null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $data = $this->getById([$id]);

        if (is_array($data)){
            return $data[0];
        }

        return null;
    }

    public function calculateFrom($page, $perPage)
    {
        return ($page>0) ? ($page-1)*$perPage:0;
    }

    private function escapeSlash($s){
        if (strlen($s)>0){
            $b = str_replace(' /','/', $s);
            $b = str_replace('/ ','/', $b);
            $b = str_replace('/','\/', $b);
            $b = str_replace('R ','R', $b);
            $b = str_replace('(','\(', $b);
            $b = str_replace(')','\)', $b);
            $b = str_replace('*','', $b);
            $b = str_replace('?','', $b);
            return $b;
        } else
            return $s;
    }

    public function productCount(){

            $this->searchParams['body']['query']['bool']['must'] = $this->must;

            $this->searchParams['body']['query']['bool']['must_not'] = $this->mustNot;

            $this->data = $this->elastic->countQuery($this->searchParams);

            return $this->data;
    }

    /**
     * @param $bucketsName
     * @param $fieldName
     * @return mixed
     */
    private function getBuckets($bucketsName, $fieldName)
    {
        $this->searchParams['body']['size'] = 0;

        $this->searchParams['body']['query']['bool']['must'] = $this->must;

        $this->searchParams['body']['query']['bool']['must_not'] = $this->mustNot;

        $this->searchParams['body']['aggregations'][$bucketsName]['terms']['field'] = $fieldName;

        $this->searchParams['body']['aggregations'][$bucketsName]['terms']['order'] = ['_term' => 'asc'];

        $this->searchParams['body']['aggregations'][$bucketsName]['terms']['size'] = 1000;

        $queryResponse = $this->elastic->executeQuery($this->searchParams);

        return $queryResponse['aggregations'][$bucketsName]['buckets'];
    }

    private function addSeasonOrder($order){

        $directive = 'season_score|desc';

        return  ($order=='')?$directive:$directive.';'.$order;

    }
}
