<?php namespace App\Http\Controllers;

use App\Commands\MichelinScrapCommand;
use Delmax\elastic\DelmaxElastic;
use Gumamax\Vehicles\Michelin\Repositories\MichelinVehiclesRepositoryInterface;

use App\Http\Requests;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MichelinVehicleController extends DmxBaseController
{
    use DispatchesJobs;

    protected $vehicleRepository;

    public function __construct(MichelinVehiclesRepositoryInterface $vehicleRepository)
    {

        parent::__construct();

        $this->vehicleRepository = $vehicleRepository;

    }

    public function apiBrands()
    {
        $top = config('gumamax.vehicle_top_brands');

        $all = $this->vehicleRepository->getBrands();

        $data = compact('top', 'all');

        return $this->respondWithData($data);

    }

    public function apiRanges($brand)
    {

        $data = $this->vehicleRepository->getRanges($brand);

        return $this->respondWithData($data);

    }

    public function apiModels($brand)
    {

        $data = $this->vehicleRepository->getModels($brand);

        return $this->respondWithData($data);

    }

    public function apiEngines($brand, $model)
    {

        $data = $this->vehicleRepository->getEngines($brand, $model);

        return $this->respondWithData($data);

    }


    public function apiYears($brand, $model, $engine)
    {

        $data = $this->vehicleRepository->getYears($brand, $model, $engine);

        return $this->respondWithData($data);

    }

    public function apiDimensions($brand, $model, $engine, $years)
    {

        $data = $this->vehicleRepository->getDimensions($brand, $model, $engine, $years);

        return $this->respondWithData($data);

    }

    public function test()
    {

        $contents = File::get(app_path() . '/Gumamax/Elastic/Mappings/test.json');

        $vehicles = json_decode($contents, true);

        $elastic = new DelmaxElastic('delmax', 'michelin_vehicles', 'id');

        $i = 0;

        foreach ($vehicles as &$vehicle) {

            $i++;

            $vehicle['id'] = $i;

        }

        $elastic->processBulkDataNew($vehicles);

    }

    public function scrap($brands)
    {
        $brands = explode('|', $brands);

        $this->dispatch(new MichelinScrapCommand($brands));

    }

    public function apiGetDimensionsBundle(Request $request){

        $vehicleCategory = $request->get('vehicle_category');

        $brand = $request->get('vehicle_brand');
        $model = $request->get('vehicle_model');
        $engine = $request->get('vehicle_engine');
        $year = $request->get('vehicle_years');

        $top = config('gumamax.vehicle_top_brands');
        $all = $this->vehicleRepository->getBrands();
        $brands = compact('top', 'all');

        $models = $this->vehicleRepository->getModels($brand);
        $engines = $this->vehicleRepository->getEngines($brand, $model);
        $years = $this->vehicleRepository->getYears($brand, $model, $engine);
        $dimensions = $this->vehicleRepository->getDimensions($brand, $model, $engine, $year);

        $bundle = compact('brands','models','engines','years', 'dimensions');

        return $this->respondWithData($bundle);

    }
}
