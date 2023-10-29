<?php

namespace App\Http\Controllers;

use Delmax\Products\BetterPrice;
use Delmax\Products\DimensionDescriptionTemplate;
use Delmax\Products\Product;
use Delmax\Products\SaveBetterPriceRequest;
use Gumamax\Products\ElasticTyresTransformerExternal;
use Gumamax\Products\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends DmxBaseController
{

    protected $repository;

    protected const unknwnVals = array("","-");

    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;

    }

    public function showShopBatteries(Request $request){
        $bestsellers = $this->repository->getBestsellersBatteries(date("Ym"),3);

        return view('shop-batteries', compact('bestsellers'));
    }

    public function showStoreItemsBatteries(Request $request){

        $resp = $this->apiBatteriesSearch($request);

        $data = json_decode($resp->content(), true);

        $products = $data['data'];

        if ($data['pagination']['per_page'] > $data['pagination']['total']) $data['pagination']['per_page'] = $data['pagination']['total'];

        $nItems = $data['pagination']['per_page'];

        $unknwVals = self::unknwnVals;

        return view('store-item-battery', compact('products', 'nItems', 'unknwVals'));
    }

    public function showSingleProduct($productId, $kind){

        switch ($kind){
            case 'guma':
                return $this->showSingleProductTyre($productId);
            case 'akumulator':
                return $this->showSingleProductBattery($productId);
            case 'ulje':
                return $this->showSingleProductOil($productId);
            case 'ratkapna':
                return $this->showSingleProductHubcap($productId);
            default:
                abort(404);
        }
    }

    public function showSingleProductBattery($productId) {

        $data = $this->repository->findBatteryById($productId);

        $featured = $this->repository->getBestsellersBatteries(date("Ymd"),4);

        if (!$data) {
            abort(404);
        }

        $product = $data;

        //dd($product);
        return view('single-product-battery', compact('product', 'featured'));
    }

    public function showSingleProductTyre($productId) {

        $data = $this->repository->findById($productId);

        $featured = $this->repository->getBestsellers(date("Ymd"),4);

        if (!$data) {
            abort(404);
        }

        $product = $data;

        $template = DimensionDescriptionTemplate::tyresTemplateArray()->toArray();

        $bestsellingDimens = [
            "165/70/R14",
            "175/65/R14",
            "185/60/R14",
            "185/60/R15",
            "185/65/R15",
            "195/65/R15",
            "205/60/R16",
            "205/55/R16",
            "225/45/R17"
        ];

        $unknwVals = self::unknwnVals;

        //dd($product);
        return view('single-product', compact('product','template', 'bestsellingDimens', 'unknwVals', 'featured'));
    }

    public function showShop(Request $request) {

        $manufacturersArray = $this->repository->getTyreBrands();

        $bestsellers = $this->repository->getBestsellers(date("Ym"),3);

        return view('shop', compact('manufacturersArray', 'bestsellers', 'request'));//, compact("products"));
    }

    public function showCompare() {
        return view('compare');
    }

    public function showStoreItems(Request $request){
        $resp = $this->apiTyresSearch($request);

        $data = json_decode($resp->content(), true);

        $products = $data['data'];

        if ($data['pagination']['per_page'] > $data['pagination']['total']) $data['pagination']['per_page'] = $data['pagination']['total'];

        $nItems = $data['pagination']['per_page'];

        $unknwVals = self::unknwnVals;

        return view('store-item', compact('products', 'nItems', 'unknwVals'));
    }

    public function fetchSingleItem($productId){
        $data = $this->repository->findById($productId);

        if (!$data){
            abort(404);
        }

        return $data;
    }

    /**
     * @param $productId
     * @return \Illuminate\View\View
     */
    public function show($productId)
    {
        $data = $this->repository->findById($productId);

        if (!$data){
            abort(404);
        }

        $product = $data;

        $template = DimensionDescriptionTemplate::tyresTemplateArray()->toArray();

        return view('product.show', compact('product','template'));
    }

    public function apiBatteriesSearch(Request $request){

        $this->setPaginationRequest($request);

        $data = $this->repository->batteriesSearch($this->order, $this->perPage,  $this->currentPage);

        if (!is_null($data)) {
            $this->total = $this->repository->getTotal();

            event('user.search', [['query'=>$this->query, 'total'=>$this->total]]);

            $items = $data['hits']['hits'];

            $data = $this->repository->transformerBatteries->transformCollection($items);

            $this->data = compact('data');
        } else {

            event('user.search', [['query'=>$this->query, 'total'=>0]]);

            $data = null;

            $this->data = compact('data');
        }

        return $this->respondWithPagination();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function apiTyresSearch(Request $request)
    {
        /*if (auth()->check() &&
            ((auth()->user()->first_name == 'BankaTest') && (auth()->user()->last_name == 'BankaTest'))) {

            return $this->BankaTestSearch();
        }*/

        $this->setQuery($request);

        $this->setPaginationRequest($request);

        $data = $this->repository->tyresSearch($this->query, $this->order, $this->perPage,  $this->currentPage);

        if (!is_null($data)) {
            $this->total = $this->repository->getTotal();

            event('user.search', [['query'=>$this->query, 'total'=>$this->total]]);

            $items = $data['hits']['hits'];

            $aggregations = $data['aggregations'];

            $data = $this->repository->transformerTyres->transformCollection($items);

            $this->data = compact('data', 'aggregations');
        } else {

            event('user.search', [['query'=>$this->query, 'total'=>0]]);

            $data = null;

            $aggregations = null;

            $this->data = compact('data', 'aggregations');
        }

        return $this->respondWithPagination();

    }

    public function apiTyresReplacements(Request $request)
    {
        $this->setQuery($request);

        $this->setPaginationRequest($request);

        $data = $this->repository->tyresReplacements($this->query, $this->order, $this->perPage,  $this->currentPage);

        if (!is_null($data)) {
            $this->total = $this->repository->getTotal();

            $items = $data['hits']['hits'];

            //$aggregations = $data['aggregations'];

            $data = $this->repository->transformerTyres->transformCollection($items);

            $this->data = compact('data');
        } else {

            $data = null;

            $aggregations = null;

            $this->data = compact('data', 'aggregations');
        }

        return $this->respondWithPagination();

    }

    public function getTyresWidths($vehicle_category){
        /*Primer ako se uzima sa API
            $res = $this->client->request('GET', 'products/tyres/dimensions/widths/'.$vehicle_category);

            $data = json_decode($res->getBody());

            return $this->respond($data);

            */
        $data = $this->repository->tyresWidths($vehicle_category);

        return $this->respondWithData($data);

    }

    public function getTyresRatios($vehicle_category, $width){

        $data = $this->repository->tyresRatios($vehicle_category, $width);

        return $this->respondWithData($data);

    }

    /**
     * @param $vehicle_category
     * @param $width
     * @param $ratio
     * @return mixed
     * @internal param $vehicleCategory
     */
    public function getTyresDiameters($vehicle_category, $width, $ratio){

        $data = $this->repository->tyresDiameters($vehicle_category, $width, $ratio);

        return $this->respondWithData($data);
    }

    /**
     * @param $productId1
     * @param $productId2
     * @param null $productId3
     * @return \Illuminate\View\View
     */
    public function compareList($productId1, $productId2=null, $productId3=null)
    {
        // http://gmx5/products/tyres/compare/174049/163107/138138

        $query = [$productId1];

        if (!is_null($productId2)) $query[] = $productId2;

        if (!is_null($productId3)) $query[] = $productId3;

        return $this->showCompare($query);

    }

    /**
     * @param $query
     * @return \Illuminate\View\View
     */
//    private function showCompare($query){
//        $ids = '';
//
//        foreach ($query as $key=>$value) {
//            $ids .=$value.'/' ;
//        }
//
//        $products = $this->repository->getById($query) ?? [];
//
//        $template = DimensionDescriptionTemplate::tyresTemplateArray() ?? [];
//
//        return view('product.compare', compact('products', 'template', 'ids'));
//
//    }

    /**
     * @return \Illuminate\View\View
     */
    public function compare(){

        $query = $this->getComparableProductsFromCookie();

        return $this->showCompare($query);

    }

    private function getComparableProductsFromCookie(){

        $productIdList = [];

        if(checkCookie('gmx_cmp_p')){

            $c = explode('|', $_COOKIE['gmx_cmp_p']);

            foreach ($c as $cookieProduct){

                $a = explode('=',$cookieProduct);

                $s = $a[0];

                $s=str_replace('"','',$s);

                $s=str_replace(',','',$s);

                if ($s!='') {

                    $productIdList[]=$s; }

            }
        }

        return $productIdList;
    }

    public function apiDimensionsSelectedBundle(Request $request){

        $vehicleCategory = $request->get('vehicle_category');

        $width = $request->get('width');

        $ratio = $request->get('ratio');

        $widths = $this->repository->tyresWidths($vehicleCategory);

        $ratios = $this->repository->tyresRatios($vehicleCategory, $width);

        $diameters = $this->repository->tyresDiameters($vehicleCategory, $width, $ratio);

        $bundle = compact('widths', 'ratios', 'diameters');

        return $this->respondWithData($bundle);
    }

    public function addBetterPrice(SaveBetterPriceRequest $request) {

        $product = Product::find($request->get('product_id'));

        $betterPrice = BetterPrice::make($request->all());

        $product->addBetterPrice($betterPrice);

        try {
            $betterPrice->save();

            event('betterPrice.created', compact('betterPrice'));

            return 'true';
        } catch(Exception $e) {
            return 'false';
        }
    }

    private function setQuery(Request $request){

        $this->query = [

            'product_id'=>$request->get('product_id',''),

            'vehicle_category'=>$request->get('vehicle_category',''),

            'width'=>$request->get('width',''),

            'ratio'=>$request->get('ratio',''),

            'diameter'=>$request->get('diameter',''),

            'season'=>$request->get('seasons',''),

            'manufacturer'=>$request->get('manufacturers',''),

            'speed_index'=>$request->get('speed_indexes',''),

            'requested_qty'=>$request->get('requested_qty','')
        ];
    }

    public function apiCount(){

        return $this->repository->productCount();

    }

    /**
     * All tyres for external user
     * @param Request $request
     * @return mixed
     */
    public function apiTyresAll(Request $request)
    {
        $this->setQuery($request);

        $this->setPaginationRequest($request);

        $data = $this->repository->tyresSearch($this->query, $this->order, $this->perPage,  $this->currentPage);

        $transformer = new ElasticTyresTransformerExternal();

        if (!is_null($data)) {
            $this->total = $this->repository->getTotal();

            event('user.search', [['query'=>$this->query, 'total'=>$this->total]]);

            $items = $data['hits']['hits'];

            $aggregations = $data['aggregations'];

            $data = $transformer->transformCollection($items);

            $this->data = compact('data', 'aggregations');
        } else {

            event('user.search', [['query'=>$this->query, 'total'=>0]]);

            $data = null;

            $aggregations = null;

            $this->data = compact('data', 'aggregations');
        }

        return $this->respond($this->data);

    }

    /*
    public function BankaTestSearch() {

        return <<<'EOT'
        {
            "pagination": {
                "total":1,
                "current_page":1,
                "last_page":1,
                "per_page":10,
                "next_page":0,
                "prev_page":0
            },
            "data":[
                {"company_id":"8000",
                "merchant_id":8080,
                "product_id":313337,
                "manufacturer_id":457,
                "manufacturer":"Kleber",
                "cat_no":"545691/2021",
                "description":"Spoljna guma",
                "description_id":1679,
                "additional_description":"205\/55 R16 Quadraxer 2 91 H",
                "uom_id":"kom",
                "packing":"1\/1",
                "dmx_primary_type":"-",
                "ean":"8600232424135",
                "cross_ref":210161,
                "note":"Kleber Dynaxer HP3 je letnji pneumatik, namenjen je za kompaktne sedane, limuzine i minivane. Dynaxer HP3 pru\u017ea odli\u010dno prianjanje i bezbednost na mokrim putevima kao i dobro le\u017eanje na suvom putu.",
                "season":"Letnja",
                "vehicle_category":"Putni\u010dko",
                "year_of_production":2021,
                "diameter":"16",
                "country_of_origin":null,
                "thumbnail_image_url_54x50":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,54,50",
                "thumbnail_image_url_80x60":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,80,60",
                "thumbnail_image_url_120x90":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,120,90",
                "thumbnail_url_40":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,40",
                "thumbnail_url_110":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,110",
                "thumbnail_url_118":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,118",
                "thumbnail_url_140x140":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,140,140",
                "image_url":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg",
                "min_order_qty":null,
                "purchase_on_demand":"",
                "max_order_qty":"0.0000",
                "stock_status":1,
                "price_with_tax":"1.0000000000",
                "price_without_tax":"0.8",
                "images":[
                    {"rownum":-1,
                    "thumbnail_url_80x60":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,80,60",
                    "thumbnail_url_120x90":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,120,90",
                    "thumbnail_url_40":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,40",
                    "image_url":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg",
                    "thumbnail_url_118":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,118",
                    "es_id":"sc1p210386",
                    "nested_id":162417,
                    "is_default":1,
                    "order_index":null,
                    "thumbnail_url_110":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,110",
                    "thumbnail_url_54x50":"https:\/\/delmaxapi.com\/_img?img=\/images\/products\/211000\/162417.jpg&transform=resize,54,50"}
                ],
                "dimensions":[
                    {"rownum":-1,"value_text":"7.7","dimension_id":1,"product_id":210386,"es_id":"sc1p210386","nested_id":"d1p210386","description":"Te\u017eina [kg]",
                    "value_num":"7.7000","order_index":15},
                    {"rownum":-1,"value_text":"Putni\u010dko","dimension_id":10,"product_id":210386,"es_id":"sc1p210386","nested_id":"d10p210386","description":"Vrsta vozila",
                    "value_num":"0.0000","order_index":1},
                    {"rownum":-1,"value_text":"195","dimension_id":11,"product_id":210386,"es_id":"sc1p210386","nested_id":"d11p210386","description":"\u0160irina gume [mm]",
                    "value_num":"0.0000","order_index":2},
                    {"rownum":-1,"value_text":"45","dimension_id":12,"product_id":210386,"es_id":"sc1p210386","nested_id":"d12p210386","description":"Visina gume [%]",
                    "value_num":"0.0000","order_index":3},
                    {"rownum":-1,"value_text":"16","dimension_id":13,"product_id":210386,"es_id":"sc1p210386","nested_id":"d13p210386","description":"Pre\u010dnik gume",
                    "value_num":"0.0000","order_index":4},
                    {"rownum":-1,"value_text":"Letnja","dimension_id":14,"product_id":210386,"es_id":"sc1p210386","nested_id":"d14p210386","description":"Sezona",
                    "value_num":"0.0000","order_index":5},
                    {"rownum":-1,"value_text":"V (240 km\/h)","dimension_id":15,"product_id":210386,"es_id":"sc1p210386","nested_id":"d15p210386","description":"Indeks brzine",
                    "value_num":"0.0000","order_index":6},
                    {"rownum":-1,"value_text":"E","dimension_id":16,"product_id":210386,"es_id":"sc1p210386","nested_id":"d16p210386","description":"U\u0161teda goriva",
                    "value_num":"0.0000","order_index":7},
                    {"rownum":-1,"value_text":"B","dimension_id":17,"product_id":210386,"es_id":"sc1p210386","nested_id":"d17p210386","description":"Prianjanje",
                    "value_num":"0.0000","order_index":8},
                    {"rownum":-1,"value_text":"1","dimension_id":18,"product_id":210386,"es_id":"sc1p210386","nested_id":"d18p210386","description":"Nivo buke",
                    "value_num":"0.0000","order_index":9},
                    {"rownum":-1,"value_text":"069","dimension_id":19,"product_id":210386,"es_id":"sc1p210386","nested_id":"d19p210386","description":"Nivo buke u dB",
                    "value_num":"0.0000","order_index":12},
                    {"rownum":-1,"value_text":"84","dimension_id":20,"product_id":210386,"es_id":"sc1p210386","nested_id":"d20p210386","description":"Indeks nosivosti",
                    "value_num":"0.0000","order_index":10},
                    {"rownum":-1,"value_text":"XL","dimension_id":21,"product_id":210386,"es_id":"sc1p210386","nested_id":"d21p210386","description":"Specifikacija gume",
                    "value_num":"0.0000","order_index":13},
                    {"rownum":-1,"value_text":"Dynaxer HP3","dimension_id":22,"product_id":210386,"es_id":"sc1p210386","nested_id":"d22p210386","description":"Profil",
                    "value_num":"0.0000","order_index":11},
                    {"rownum":-1,"value_text":"2016","dimension_id":25,"product_id":210386,"es_id":"sc1p210386","nested_id":"d25p210386","description":"Godina proizvodnje",
                    "value_num":"2016.0000","order_index":14}
                ],
                "action_price":null,
                "list_price":"1.00",
                "super_price":"1.0000000000",
                "discount":"0.0000",
                "stock_status_qty":"1.0000",
                "rating":0,
                "product_weight":"7.7",
                "tax_id":4,
                "tax_rate":"20.00",
                "eu_badge":{"consumption":"E","grip":"B","noise":"1","noise_db":"069"}
            }],
            "aggregations":{
                "seasons":{
                    "doc_count_error_upper_bound":0,
                    "sum_other_doc_count":0,
                    "buckets":[{"key":"Letnja","doc_count":1}]
                },
                "manufacturers":{
                    "doc_count_error_upper_bound":0,
                    "sum_other_doc_count":0,
                    "buckets":[{"key":"Kleber","doc_count":1}]
                },
                "speed_indexes":{
                    "doc_count_error_upper_bound":0,
                    "sum_other_doc_count":0,
                    "buckets":[{"key":"V (240 km\/h)","doc_count":1}]
                }
            }
        }
        EOT;
    }*/

}
