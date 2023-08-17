<?php namespace Gumamax\Vehicles\Michelin\Repositories;

use Delmax\elastic\DelmaxElastic;
use Gumamax\Vehicles\Michelin\ElasticMichelinDimensionsTransformer;
use Gumamax\Vehicles\Michelin\ElasticMichelinVehiclesTransformer;
use Gumamax\Vehicles\Michelin\ElasticMichelinModelsTransformer;


/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 25.3.2015
 * Time: 20:59
 */


class EsMichelinVehiclesRepository implements MichelinVehiclesRepositoryInterface{

    protected $transformer;

    protected $elastic;

    public function __construct(){

        $this->transformer = new ElasticMichelinVehiclesTransformer();

        $this->elastic = new DelmaxElastic('michelin', 'vehicles', 'id');

    }

    public function getBrands()
    {
        return $this->getAggregations('brands', 'brand.facet', null, 'count');
    }

    public function getRanges($brand)
    {
        if ($brand!=='') {

            $must = [
                ['term'=>['brand.facet'=>$brand]]
            ];

            return $this->getAggregations('ranges', 'range.facet', $must, 'count');
        }
    }

    public function getModels($brand)
    {
        if ($brand != '') {

            $must = [
                ['term' => ['brand.facet' => $brand]],
            ];

            $searchParams['body']['size']=1000;
            $searchParams['body']['query']['bool']['must'] = $must;

            $searchParams['body']['aggregations']['ranges']['terms']['field'] = 'range.facet';
            $searchParams['body']['aggregations']['ranges']['terms']['order'] = ['_term'=>'asc'];
            $searchParams['body']['aggregations']['ranges']['terms']['size']  = 1000;

            $searchParams['body']['aggregations']['ranges']['aggregations']['models']['terms']['field'] = 'model.facet';
            $searchParams['body']['aggregations']['ranges']['aggregations']['models']['terms']['order'] = ['_term'=>'asc'];
            $searchParams['body']['aggregations']['ranges']['aggregations']['models']['terms']['size']  = 1000;


            $queryResponse = $this->elastic->executeQuery($searchParams);


            $ranges = $queryResponse['aggregations']['ranges']['buckets'];

            $modelTransformer = new ElasticMichelinModelsTransformer();

            return $modelTransformer->transformCollection($ranges);

        }
    }

    public function getEngines($brand, $model)
    {
        if (($brand != '') && ($model != '')) {

            $must = [
                ['term' => ['brand.facet' => $brand]],
                ['term' => ['model.facet' => $model]],
            ];

            return $this->getAggregations('engines', 'engine.facet', $must, 'count');
        }
    }


    public function getYears($brand, $model, $engine)
    {
        if (($brand != '') && ($model != '') && ($engine != '')) {

            $must = [
                ['term' => ['brand.facet' => $brand]],
                ['term' => ['model.facet' => $model]],
                ['term' => ['engine.facet' => $engine]]
            ];

            return $this->getAggregations('years', 'production.facet', $must, 'count');
        }
    }

    public function getDimensions($brand, $model, $engine, $year)
    {
        if (($brand != '') && ($model != '') && ($engine != '')&& ($year != '')) {

            $must = [
                ['term' => ['brand.facet' => $brand]],
                ['term' => ['model.facet' => $model]],
                ['term' => ['engine.facet' => $engine]],
                ['term' => ['production.facet' => $year]],
            ];

            $searchParams['body']['query']['bool']['must'] = $must;

            $queryResponse = $this->elastic->executeQuery($searchParams);

            $dimTransformer = new ElasticMichelinDimensionsTransformer();

            $pack =  $dimTransformer->transformCollection($queryResponse['hits']['hits']);
            $i=0;
            $dimensions=[];

            foreach($pack[0]['dimensions'] as $dimensionPackage){
                $i++;
                $dimensions[]= [
                    'package_id'=>$i,
                    'package'=>$dimensionPackage
                ];
            }
            return $dimensions;

        }
    }

    public function getDimensions2($brand, $model, $engine, $years)
    {
        if (($brand !== '') && ($model !== '') && ($engine !== '')&& ($years !== '')) {

            $must = [
                ['term' => ['brand.facet' => $brand]],
                ['term' => ['model.facet' => $model]],
                ['term' => ['engine.facet' => $engine]],
                ['term' => ['production.facet' => $years]],
            ];

            $searchParams['body']['query']['bool']['must'] = $must;

            $searchParams['body']['aggregations']['radials']['terms']['field'] = 'dimensions.radial';

            $searchParams['body']['aggregations']['radials']['terms']['order'] = ['_term'=>'asc'];

            $searchParams['body']['aggregations']['radials']['terms']['size']  = 1000;


            $queryResponse = $this->elastic->executeQuery($searchParams);

            $dimensions = $queryResponse['hits']['hits'];


            return $this->transformer->transformCollection($dimensions);

        }
    }

    private function getAggregations($aggName, $aggField, $must=null, $searchType=null)
    {
            if ($must)
                $searchParams['body']['query']['bool']['must'] = $must;

            if ($searchType)
                $searchParams['search_type']  = $searchType;

            $searchParams['body']['aggregations'][$aggName]['terms']['field'] = $aggField;

            $searchParams['body']['aggregations'][$aggName]['terms']['order'] = ['_term'=>'asc'];

            $searchParams['body']['aggregations'][$aggName]['terms']['size']  = 1000;


            $queryResponse = $this->elastic->executeQuery($searchParams);

            if ($searchType)
                return $queryResponse['aggregations'][$aggName]['buckets'];
            else
                return $queryResponse;
    }


}