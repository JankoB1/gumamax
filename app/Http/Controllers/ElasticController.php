<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 10:01
 */

namespace App\Http\Controllers;

use Delmax\elastic\DelmaxElastic;
use Elastic\Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ElasticController extends DmxBaseController {

    private $elasticServiceIsRunning = false;

    private $client;

    public function __construct(){

        parent::__construct();

        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->build();

        try {
            $this->elasticServiceIsRunning = $this->client->ping();

        } catch (Exception $e) {
            session()->flash('flash_error', $e->getMessage());
            $this->elasticServiceIsRunning = false;

        }
    }

    public function index(){

        $esServiceInfo = $this->esServiceInfo();

        $stats =  $this->client->indices()->stats();

        $indices = $stats['indices'];

        $appIndices = config('elasticsearch.indices');

        foreach($appIndices as $key=>&$appIndex){
            $indexName = $key;
            $typeName  = $appIndex['type'];
            if (array_key_exists($indexName, $indices)){
                $appIndex['exists'] = true;
                $appIndex['total']['docs']['count']   = $indices[$indexName]['total']['docs']['count'];
                $appIndex['total']['docs']['deleted'] = $indices[$indexName]['total']['docs']['deleted'];
                $appIndex['type_exists'] = $this->client->indices()->exists(['index'=>$indexName, 'type'=>$typeName]);
            }  else {
                $appIndex['exists'] = false;
            }
        }

        return view('admin.elastic.index', compact('esServiceInfo', 'appIndices'));

    }

    public function esServiceInfo(){

        try{

            return json_encode($this->client->info(), JSON_PRETTY_PRINT);

        } catch (Exception $e) {

            return $e->getMessage();
        }

    }


    public function indexCreate(Request $request){

        $indexName = $request->input('index_name','');
        $delmaxElastic = new DelmaxElastic($indexName);
       try {
           $contents = File::get(app_path().'/Gumamax/Elastic/Mappings/'.$indexName.'.json');

           $body = json_decode($contents, true);

           $result = json_encode($delmaxElastic->createIndex($body));

           return redirect(route('admin.elastic.index'))->with('flash_notice', $result);

       } catch (Exception $e) {

           return redirect(route('admin.elastic.index'))->with('flash_error', $e->getMessage());
       }

    }

    public function indexDelete(Request $request){

        $indexName = $request->input('index_name');
        $delmaxElastic = new DelmaxElastic($indexName);
        try{
            $result = json_encode($delmaxElastic->deleteIndex());

            return redirect(route('admin.elastic.index'))->with('flash_notice', $result);

        } catch (Exception $e){
            return redirect(route('admin.elastic.index'))->with('flash_error', $e->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function typeCreate(Request $request){

        $indexName  =  $request->input('index_name');

        $typeName   =  $request->input('type_name');


        try {
            $contents = File::get(app_path().'/Gumamax/Elastic/Mappings/'.$typeName.'.json');

            $body = json_decode($contents, true);

            $delmaxElastic = new DelmaxElastic($indexName);

            $result = $delmaxElastic->createType($typeName, $body);

            $result = json_encode($result);

            return redirect(route('admin.elastic.index'))->with('flash_notice', $result);

        } catch (Exception $e) {
            return redirect(route('admin.elastic.index'))->with('flash_error', $e->getMessage());
        }


    }

}
