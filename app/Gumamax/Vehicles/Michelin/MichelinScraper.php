<?php namespace Gumamax\Vehicles\Michelin;

/*
Todo
[X] Upisivanje sezone kod guma -> ne treba (N.M.)
[X] Napraviti id za svaku objekat
[X] Izbaciti ponavljanje str_replace
[ ] Skrepovanje zimskih guma
[X] Napraviti klasu
[ ] Napraviti snimanje svakog pojedinačnog rekorda
[X] curl za skidanje podataka
*/

use Delmax\elastic\DelmaxElastic;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

class MichelinScraper {


    public $data        = [];

    public $brands     = ["abarth", "acura", "alfa romeo", "alpina", "aro", "artega", "asia", "aston martin", "audi", "auverland", "baic", "bentley", "bertone", "bmw", "brabus", "brilliance", "bugatti", "buick", "byd", "cadillac", "chana", "chery", "chevrolet", "chrysler", "citroen", "dacia", "daewoo", "daihatsu", "datsun", "de tomaso", "dodge", "dodge-ram", "faw", "ferrari", "fiat", "fisker", "ford", "foton", "geely", "gmc", "gonow", "great wall", "gumpert", "hafei", "haima", "hommel", "honda", "hummer", "hyundai", "infiniti", "iran khodro co", "isuzu", "iveco", "jac", "jaguar", "jeep", "jmc", "kia", "koenigsegg", "ktm", "lada", "lamborghini", "lancia", "land rover", "lexus", "lifan", "lincoln", "lotus", "luxgen", "mahindra", "maserati", "maybach", "mazda", "mclaren", "meiya", "mercedes", "mercury", "mg rover", "mia electric", "mini", "mitsubishi", "nissan", "opel", "pagani", "perodua", "peugeot", "pgo", "pontiac", "porsche", "proton", "renault", "rolls royce", "rover", "saab", "santana", "seat", "skoda", "smart", "spyker", "ssangyong", "subaru", "suzuki", "tata", "tesla", "toyota", "tvr", "vauxhall", "volkswagen", "volvo", "wiesmann", "yugo", "zenvo", "zna", "zotye", "zx auto", "ваз", "газ", "уаз"];
    private $brandURL	= "http://www.michelin.rs/tyreSearch/tyreSelectorSearchOption.action?outputType=1&tyreSegment=123&optionName=range&dependencyValue=rangeDependencyValue&sortOrder=null&brandDependencyValue=";
    private $rangeURL	= "http://www.michelin.rs/tyreSearch/tyreSelectorSearchOption.action?outputType=1&tyreSegment=123&optionName=model&dependencyValue=modelDependencyValue&sortOrder=null&brandDependencyValue=";
    private $modelURL	= "http://www.michelin.rs/tyreSearch/tyreSelectorSearchOption.action?outputType=1&tyreSegment=123&optionName=engine&dependencyValue=engineDependencyValue&sortOrder=null&brandDependencyValue=";
    private $engineURL	= "http://www.michelin.rs/tyreSearch/tyreSelectorSearchOption.action?outputType=1&tyreSegment=123&optionName=year&dependencyValue=yearDependencyValue&sortOrder=null&brandDependencyValue=";
    private $tiresURL	= "http://www.michelin.rs/tyreSearch/tyreSelectorSearchResult.action?brand=";

    private $rangeDependency 	= "&rangeDependencyValue=";
    private $modelDependency 	= "&modelDependencyValue=";
    private $engineDependency 	= "&engineDependencyValue=";

    private $rangeParam 		= "&range=";
    private $modelParam 		= "&model=";
    private $engineParam 		= "&engine=";
    private $yearParam 			= "&year=";
    private $endURL 			= "&selectedFilter=summer&needFitments=true&showPressure=true";

    private $id;

    private $client;

    private $requestCounter = 0;

    public function __construct(){

        $this->client = new Client();

    }

    public function getData($from = 0, $numberOfResults = 2){

        for($i = $from; $i < $from+$numberOfResults; $i++){

            $this->getDataForBrands([$this->brands[$i]]);

        }
    }

    public function getDataForBrands($brands){

        foreach ($brands as $brand){
            $this->getDataForBrand($brand);
        }
    }

    public function getDataForBrand($brand, DelmaxElastic $elastic=null){

            $qBrand = str_replace(" ", "+", $brand);
            $ranges = $this->getDataFromURL($this->brandURL.$qBrand);
            $modelCounter = 0;
            foreach($ranges->searchOptions as $range){
                $qRange = str_replace(" ", "+", $range->value);
                $models = $this->getDataFromURL(
                    $this->rangeURL.$qBrand.
                    $this->rangeDependency.$qRange
                );

                foreach($models->searchOptions as $model){
                    $qModel = str_replace(" ", "+", $model->value);
                    $engines = $this->getDataFromURL(
                        $this->modelURL.$qBrand.
                        $this->rangeDependency.$qRange.
                        $this->modelDependency.$qModel
                    );

                    foreach($engines->searchOptions as $engine){
                        $qEngine = str_replace(" ", "+", $engine->value);

                        $years = $this->getDataFromURL(
                            $this->engineURL.$qBrand.
                            $this->rangeDependency.$qRange.
                            $this->modelDependency.$qModel.
                            $this->engineDependency.$qEngine
                        );

                        foreach($years->searchOptions as $year){
                            $qYear = str_replace(" ", "+", $year->value);
                            $tires = $this->getDataFromURL(
                                $this->tiresURL.$qBrand.
                                $this->rangeParam.$qRange.
                                $this->modelParam.$qModel.
                                $this->engineParam.$qEngine.
                                $this->yearParam.$qYear.
                                $this->endURL
                            );

                            $dimensions = array();
                            foreach($tires->Fitments as $tire){
                                $dimensions[] = $tire->Dimensions;
                            }

                            $production = $year->value;
                            $production_start = $year->value;
                            $production_end = $year->value;
                            if(strpos($production, '-')){
                                $tmp = explode('-', $production);
                                $production_start = trim($tmp[0]);
                                $production_end = trim($tmp[1]);
                            }
                            $modelCounter++;

                            $this->id=$this->generateUid();

                            $doc = [
                                "id" => $this->id,
                                "brand" => strtoupper($brand),
                                "range" => strtoupper($range->value),
                                "model" => strtoupper($model->value),
                                "engine" => strtoupper($engine->value),
                                "production" => $production,
                                "production_start" => $production_start,
                                "production_end" => $production_end,
                                "dimensions" => $dimensions
                            ];

                            $this->data[]=$doc;

                            if ($modelCounter%500==0){
                                $elastic->bulkIndex($this->data);
                                $this->data=[];
                            }


                        }
                    }
                }
            }
            if (count($this->data)>0){
                $elastic->bulkIndex($this->data);
                $this->data=[];
            }
        }
    /**
     * @param $url
     * @return mixed
     */
    public function getDataFromURL($url){

        $this->requestCounter++;

        if (($this->requestCounter % 150)==0){

            sleep(rand(1,3));

        }
        $response = $this->client->get($url);


        return \GuzzleHttp\json_decode($response->getBody());
        //return $response->json(['object'=>true]);

    }

    protected function generateUid()
    {
        return Hash::make(rand() . uniqid(null, true));
    }

    public function saveToFile($fileName){

        $this->getData();

        file_put_contents($fileName, $this->data);

    }

}



