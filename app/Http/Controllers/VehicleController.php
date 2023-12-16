<?php namespace App\Http\Controllers;


use Gumamax\Vehicles\TcdVehicles;
use Gumamax\Vehicles\UserVehicle;
use Illuminate\Http\Request;


class VehicleController extends Controller {

	private $tcd;

    public function __construct(TcdVehicles $tcd){

        $this->tcd = $tcd;

    }

    public function index(Request $request){

        $action = $request->get('a');
        $year   = $request->get('year');
        $mfaId  = $request->get('mfa_id');
        $modId  = $request->get('mod_id');
        $typId = $request->get('typ_id');
        $name   = $request->get('commercial_description');
        $vin    = $request->get('vin');
        $engineCode = $request->get('engine_code');

        if($action == 'manufacturersByYear')
            $this->manufacturersByYear($year);
        else if($action == 'modelsByYear')
            $this->modelsByYear($year, $mfaId);
        else if($action == 'vehiclesByYear')
            $this->vehiclesByYear($year, $mfaId, $modId);
        else if($action == 'insertVehicle'){
            $vehicle = UserVehicle::makeByValues($year, $mfaId, $modId, $typId, $name, $vin, $engineCode);
            return auth()->user()->addVehicle($vehicle);
        }
        else if($action == 'deleteVehicle')
            $this->userVehicle->deleteVehicle($request->get('id'));
        else echo json_encode(array("action" => $action));
    }

    public function manufacturersByYear($year=0){
        echo $this->tcd->manufacturer($year);
    }

    public function modelsByYear($year, $manufacturerId){
        echo $this->tcd->model($year, $manufacturerId);
    }

    public function vehiclesByYear($year, $manufacturerId, $modelId){
        echo $this->tcd->type($year, $manufacturerId, $modelId);
    }

}
