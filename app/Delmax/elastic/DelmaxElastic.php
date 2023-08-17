<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 18.6.14.
 * Time: 11.04
 *
 * Update:
 * 12.08.2016 2.3 support
 *
 */

namespace Delmax\elastic;

use Elastic\Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Support\Facades\Log;


class DelmaxElastic
{
    protected $rest;
    public $esClient;
    protected $esPrimaryUrl;
    protected $indexName;
    protected $typeName;
    protected $repository;
    protected $idFieldName;
    protected $queryParams=[];
    public $queryString;
    public $boolQuery;
    protected $serviceProvider;
    /**
     * @param string $indexName
     * @param string $typeName
     * @param string $idFieldName
     * @param null $repository
     */
    public function __construct($indexName='', $typeName='', $idFieldName='',  $repository=null){

        $this->esClient = ClientBuilder::create()
            ->setHosts(['127.0.0.1:9200'])
            ->build();

        $this->indexName    = $indexName;
        $this->typeName     = $typeName;
        $this->repository   = $repository;
        $this->idFieldName  = $idFieldName;
    }

    public static function getEsUrl(){

        $hosts = config('elasticsearch.hosts');

        return $hosts[0];

    }


    /**
     * Create Index
     * @param $indexJson - settings for index
     * @return mixed|string
     */
    public function createIndex($indexJson=null){
        try {

            $indexParams['index'] = $this->indexName;

            if (!is_null($indexJson)){

                $indexParams['body']= $indexJson;

            }

            return $this->esClient->indices()->create($indexParams);

        } catch (Exception $e) {

            return $e->getMessage();

        }
    }

    /**
     * Delete Index
     * @return mixed|string
     */
    public function deleteIndex(){
        try {

            $indexParams['index'] = $this->indexName;

            return $this->esClient->indices()->delete($indexParams);

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Create type
     * @param $typeName
     * @param $mappingJson
     * @return mixed|string
     */
    public function createType($typeName, $mappingJson){

        try {

            $typeParams['index'] = $this->indexName;

            $typeParams['type'] = $typeName;

            $typeParams['body'] = $mappingJson;

            return $this->esClient->indices()->putMapping($typeParams);

        } catch (Exception $e) {

            return $e->getMessage();

        }
    }

    /**
     * @param $id
     * @param $docData
     * @return array
     */
    public function indexTypeUpdateOrInsertDocument($id, $docData){
        $esData = [];
        $esData['index'] = $this->indexName;
        $esData['type']  = $this->typeName;
        $esData['id']    = $id;
        $esData['body']  = (array) $docData;
        return $this->esClient->index($esData);
    }

    /**
     * @param $id
     * @param $docData
     * @return array
     */
    public function indexTypeUpdateDocument($id, $docData){
        $esData = [];
        $esData['index'] = $this->indexName;
        $esData['type']  = $this->typeName;
        $esData['id']    = $id;
        $esData['body']['doc']  = (array) $docData;
        return $this->esClient->update($esData);
    }

    public function updateNestedDocument($id, $nestedName, $nestedArray){
        $esData = [];
        $esData['index'] = $this->indexName;
        $esData['type']  = $this->typeName;
        $esData['id']    = $id;
        $esData['body']['doc'][$nestedName] = $nestedArray;
        return $this->esClient->update($esData);
    }

    /**
     * @param $searchParams
     * @param $order
     */

    public function setOrder(&$searchParams, $order){

        $orderRules = $this->esDecodeOrder($order);

        foreach($orderRules as $orderRule) {
            $searchParams['body']['sort'][]=[
                $orderRule['field']=>[
                    'order'=>$orderRule['way'],
                    'ignore_unmapped' => true
                ]
            ];
        }
    }

    /**
     * @param $documents
     * @param int $bulkDocCount
     * @return array
     */
    public function bulkIndex($documents, $bulkDocCount=1000){
        $docCount = 0 ;
        $bulkData = [];
        $response = [];
        foreach ($documents as $doc){
            $docCount++;
            $bulkData [] = (array) $doc;
            if ($docCount%$bulkDocCount==0){
                $response = $this->bulkExecute($bulkData);
                $docCount = 0;
                $bulkData = [];
            }
        }
        if (count($bulkData) > 0)
            $response = $this->bulkExecute($bulkData);

        return $response;
    }

    /**
     * @param $documents
     * @param int $bulkCount default 1000
     * @return mixed @elapsedTime
     */
    public function processBulkData($documents, $bulkCount=1000)
    {
        $time_start = microtime(true);
        $this->bulkIndex($documents, $bulkCount);
        $time_end = microtime(true);
        $elapsedTime = $time_end - $time_start;
        return $elapsedTime;
    }

    /**
     * @param $esBulkArr
     * @return array
     */
    private function bulkExecute($esBulkArr){

        $esData = [];
        $esData['index'] = $this->indexName;
        $esData['type']  = $this->typeName;


        foreach($esBulkArr as $doc) {
            $esData['body'][] = [
                'index' => [
                    '_id' => $doc[$this->idFieldName]
                ]
            ];

            $esData['body'][] = $doc;
        }

        return $this->esClient->bulk($esData);
    }


    public function executeQuery($searchParams){
        try {
            $searchParams['index']=$this->indexName;
            $searchParams['type']=$this->typeName;
            return $this->esClient->search($searchParams);
        } catch (Exception $e)  {
            Log::error(__CLASS__. __METHOD__. '(): '. $e->getMessage());
            return null;
        }
    }

    public function countQuery($searchParams){
        try {
            $searchParams['index']=$this->indexName;
            $searchParams['type']=$this->typeName;
            return $this->esClient->count($searchParams);
        } catch (Exception $e)  {
            Log::info(__CLASS__. __METHOD__. '(): '. $e->getMessage());
            return null;
        }
    }

    public function buildEsQuery($query){
    {

        $this->queryParams = array_merge($this->queryParams, $this->serviceProvider);

        foreach ($this->queryParams as $key=>$value)
        {
            /** @var $value */

            if ($value!='') {

                $queryStr =  esEscapeSlash($value);

                $queryStr = esSpaceToQuestionMark($queryStr);

                $this->addToQuery('('.$key.':'.$queryStr.')');

            }
        }

        foreach ($query as $key=>$value)
        {
            /** @var $value */

            if ($value!='') {

                $queryStr =  esEscapeSlash($value);

                $queryStr = esSpaceToQuestionMark($queryStr);

                $queryStr = $queryStr.'*';

                $this->addToQuery('('.$key.':'.$queryStr.')');

            }
        }

    }
    }

    /**
     * @param $condition
     */
    public function addToQuery($condition){

        if (trim($condition)!=''){

            $this->queryString .=  ($this->queryString !='') ? ' AND ' : '';

            $this->queryString .= ' '.$condition;
        }
    }

    public function buildEsBoolQuery($query){
        {
            $this->queryParams = array_merge($this->queryParams, $this->serviceProvider, ['visible'=>1]);

            foreach ($this->queryParams as $key=>$value)
            {
                /** @var $value */

                if ($value!='') {

                    $queryStr =  esEscapeSlash($value);

                    $queryStr = esSpaceToQuestionMark($queryStr);

                    $this->addToBoolQueryMatch([$key=>$queryStr]);

                }
            }

            foreach ($query as $key=>$value)
            {
                /** @var $value */

                if ($value!='') {

                    $queryStr =  esEscapeSlash($value);

                    $queryStr = strtolower($queryStr);

                    $queryStr = esDecodeSearchString($queryStr);

                    $this->addToBoolQueryWildcard([$key=>$queryStr]);

                }
            }
        }
    }

    /**
     * @param $condition
     */
    public function addToBoolQueryMatch(Array $condition){

        $this->boolQuery['bool']['must'][] = ['match'=>$condition];

    }

    public function addToBoolQueryWildcard(Array $condition){

        $searchField = key($condition);

        $searchString = $condition[$searchField];

        $searchStringArray = explode(' ', $searchString);

        foreach ($searchStringArray as $word) {
            $this->boolQuery['bool']['must'][] = ['wildcard' => [$searchField => $word]];
        }
    }

    private function esDecodeOrder($order, $delimiter='|', $fieldsDelimiter=';')
    {
        $rules=[];

        $fieldRules = explode($fieldsDelimiter, $order);

        foreach($fieldRules as $fieldRule){

            $tmp = explode($delimiter, $fieldRule);

            $rule=[];

            if (count($tmp)>0){
                $rule['field'] = $tmp[0];

                if (isset($tmp[1])){
                    $rule['way']   = $tmp[1];
                } else {
                    $rule['way']   = 'asc';
                }

                $rules[]=$rule;
            }
        }

        return $rules;
    }
}
