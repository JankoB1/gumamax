<?php
namespace App\Http\Controllers;

use App\Gumamax\Users\SaveAccountBasicInfoJob;
use App\Gumamax\Users\SaveAccountBasicInfoRequest;
use Gumamax\Partners\Partner;
use App\Gumamax\Users\Guest;

use Delmax\User\ChangePasswordJob;
use Delmax\User\ChangePasswordRequest;
use Delmax\Addresses\Address;
use Delmax\Addresses\SaveAddressRequest;
use Delmax\User\SaveCustomerRequest;
use Delmax\User\SavePasswordRequest;
use Delmax\User\SaveUserRequest;
use Delmax\User\UserCustomer;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\App;

use App\Models\User;
use Gumamax\Vehicles\UserVehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ProfileController extends DmxBaseController {

	use DispatchesJobs;

	private $user_id;
	private $user;
	private $userVehicle;
	/**
	 * @var Address
	 */
	private $address;
	/**
	 * @var UserCustomer
	 */
	private $customer;


	public function __construct(User $user, Address $address, UserCustomer $customer){

		parent::__construct();

		$this->user = $user;
		$this->address = $address;
		$this->customer = $customer;
	}

	public function show($user_id=null){

		if ($user_id){
			/**
			 * Admin gleda profile korisnika
			 */
			if (auth()->user()->hasRole('superadmin') || (auth()->user()->user_id==$user_id)){

				$user = User::find($user_id);

			} else {

				abort(401);

			}

		} else {

			/**
			 * Korisnik gleda svoj profil
			 */

			$user = auth()->user();
		}

		if (auth()->user()->hasRole(['gmx_partner', 'superadmin'])){

			$this->address = $user->addresses()->account()->first();

			if (!$this->address){

				$this->address = Address::make(['addressable_type'=>User::class,
					'address_type_id'=>5, 'addressable_id'=>$user->user_id, 'default_address'=>1,
					'recipient'=>$user->first_name. ' ' .$user->last_name]);
			}

			$this->customer = $user->customer;

			if (!$this->customer){
				$this->customer = UserCustomer::make(['user_id'=>$user->user_id, 'customer_type_id'=>1, 'receive_newsletter'=>0,
						'company_name'=>null, 'tax_identification_number'=>null]);
			}
			$address = $this->address;
			$customer = $this->customer;
			return view('admin.profile.show', compact('user', 'address', 'customer'));

		} else {

			$isPartner=false;
			$subdomain = session('subdomain','');	
			return view('account.personal.show', compact('user', 'subdomain', 'isPartner'));
		}

	}

	public function adminEdit($user_id){

		return $this->show($user_id);

	}

	public function edit($id){
		/**
		 * Admin edituje profil korisnika
		 * 
		 */

		/**
         * @var User
         */
		$user = auth()->user();
		//$partner = $user->partners()->first();

		$subdomain = session('subdomain','');

		if ($user->hasRole('gmx_partner')&&($user->partners()->first())){

			/**
			 * Editovanje profila partnera sa korisnickog dela sajta
			 */
			// return view('account.partner.show', compact('user', 'subdomain'));
			/**
			 * Editovanje profila partnera iz admin dela
			 */
			return view('admin.profile.user.show');

		} else {

			return view('account.personal.show', compact('user', 'subdomain'));
		}
	}


	/**
	 * Update account from main site
	 *
	 * @param SaveAccountBasicInfoRequest $request
	 * @param $id
	 * @return mixed
	 */
	public function update(SaveAccountBasicInfoRequest $request, $id){

		SaveAccountBasicInfoJob::dispatch($request->all());

		return redirect()->route('profile.show', [$id, 'tab'=>'account']);

	}

	/**
	 * Update account from admin
	 *
	 * @param SaveUserRequest $request
	 * @param $user_id
	 * @return mixed
	 */
	public function userUpdate(SaveUserRequest $request, $user_id){

		$this->user = User::find($user_id);

		if ($this->user){

			$this->user->update($request->only(['first_name', 'last_name', 'phone_number']));

			if ($request->ajax()){

				return $this->respond($this->user);

			} else {

				return redirect()->route('admin.profile', [$this->user]);

			}
		}
	}

	public function customerUpdate(SaveCustomerRequest $request, $user_id){

		$this->customer = UserCustomer::where(['user_id'=>$user_id])->first();

		if ($this->customer){

			$this->customer->update($request->only(['customer_type_id', 'company_name', 'tax_identification_number']));

		} else {
			$this->user = User::find($user_id);

			$customer = new UserCustomer($request->all());

			$this->customer = $this->user->addAsCustomer($customer);
		}

		if ($request->ajax()){

			$this->customer->load('customerType');

			return $this->respond($this->customer);

		} else {

			return redirect()->route('admin.profile', [$user_id]);

		}
	}

	public function addressUpdate(SaveAddressRequest $request, $user_id){

		$this->address = Address::where(['addressable_type'=>'App\Models\User', 'addressable_id'=>$user_id, 'address_type_id'=>5])->first();

		if ($this->address){

			$this->address->update($request->all());

		} else {
			$this->user = User::find($user_id);

			$address = new Address($request->all());

			$this->address = $this->user->addAddress($address);
		}

		if ($request->ajax()){

			return $this->respond($this->address);

		} else {

			return redirect()->route('admin.profile', [$user_id]);

		}
	}

	public function updatePassword(SavePasswordRequest $request, $user_id)
	{
		if ($this->dispatchFrom(ChangePasswordJob::class, $request)){
			if ($request->ajax()){

				return $this->respondWithInfo(_('Your password has been changed successfully!'));

			} else {

				return redirect()->route('admin.profile', [$user_id]);

			}
		};
	}

	public function changePassword(ChangePasswordRequest $request)
	{
		ChangePasswordJob::dispatch($request->all());
	}

	public function disableAccount()
	{
		auth()->user()->disableAccount();

		auth()->logout();

		session()->forget('user_id');

		flash()->overlay('Gumamax','Deaktivirali ste Vaš nalog', 'info');

		return redirect()->to('/');
	}

	public function deleteVehicle($vehicleId) {

		$user = auth()->user();

		if ($user){
			$userVehicle = UserVehicle::where(['user_vehicle_id'=>$vehicleId, 'user_id'=>$user->user_id])->delete();			
		}

	}

	public function updateVehicle($vid) {
		DB::connection('ApiDB')
			->table('user_vehicle')
			->where('user_vehicle_id', $vid)
			->update([
				'vin' => Request::get('vehicle_vin'),
				'engine_code' => Request::get('vehicle_engine')
			]);
	}


/**
 * ****************************************************************************
 *  	Partner's profile methods *********************************************
 * ****************************************************************************
 */
	public function savePartnerSiteText($partner_id)
	{
		
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerSiteText($partner_id);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=site-text');
				return redirect($url);
			} else {
				return redirect()->back()->withInput()->withErrors($res['errors']);
			}
            return App::make('PartnerController')->savePartnerWebText($partner_id);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerContact($partner_id)
	{
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerContact($partner_id);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=contact');
				return redirect($url);
			}
			else
				return redirect()->back()->withInput()->withErrors($res['errors']);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerWorkingHours($partner_id)
	{
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerWorkingHours($partner_id);
			// dd($res);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=whours');
				return redirect($url);
			} else
				return redirect()->back()->withInput()->withErrors($res['errors']);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerPaymentMethods($partner_id)
	{
		// NOTE: na sajtu stoji: "Za promenu podataka na ovoj stranici, obratite se Gumamax-u"
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerPaymentMethods($partner_id);
			// dd($res);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=payment');
				return redirect($url);
			} else
				return redirect()->back()->withInput()->withErrors($res['errors']);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerWheelAlignment($partner_id)
	{
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerWheelAlignment($partner_id);
			// dd($res);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=walign');
				return redirect($url);
			} else
				return redirect()->back()->withInput()->withErrors($res['errors']);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerMobileService($partner_id)
	{
		if(Auth::user()->belongsToPartner($partner_id)) {
			$res = (new Partner)->savePartnerMobileService($partner_id);
			// dd($res);
			if ($res['status'])
			{
				$url = (Request::is('admin/*')) ? url("/admin/partners/$partner_id") : url('/profile?tab=mobile-service');
				return redirect($url);
			} else
				return redirect()->back()->withInput()->withErrors($res['errors']);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerData($partner_id)
	{
		if(Auth::user()->belongsToPartner($partner_id)) {
            App::make('PartnerController')->savePartnerBasic($partner_id);
            return App::make('PartnerController')->savePartnerDataOther($partner_id);
		} else {
			// sta uraditi ako je nekako user pokusao da
			// izmeni podatke za nekog drugog partnera?
			return Redirect::to('/');
		}
	}

	public function savePartnerLogo()
	{
		$file = Request::file('file');
		$service_signup_id = Request::get('signup_id');
		$destPath = "img/partners/$service_signup_id";
		if ($file) {
			if (! File::exists($destPath))	File::makeDirectory($destPath);
			$destPath .= '/';
			$upload_success = $file->move($destPath,'logo.png');
			if ($upload_success) {
				if (Request::ajax()) return Response::json('success', 200);
				else return Redirect::to('profile?tab=pics');
			} else {
				if (Request::ajax()) return Response::json('error', 400);
				else return Redirect::back()->withErrors(['errors'=>'Dodavanje logoa nije uspelo']);
			}
		} else {
			return Redirect::to('profile?tab=pics');
		}
	}

	public function savePartnerCover()
	{
		$file = Request::file('file');
		$service_signup_id = Request::get('signup_id');
		$destPath = "img/partners/$service_signup_id";
		if ($file) {
			if (! File::exists($destPath))	File::makeDirectory($destPath);
			$destPath .= '/';
			$upload_success = $file->move($destPath,'cover.jpg');
			if ($upload_success) {
				if (Request::ajax()) return Response::json('success', 200);
				else return Redirect::to('profile?tab=pics');
			} else {
				if (Request::ajax()) return Response::json('error', 400);
				else return Redirect::back()->withErrors(['errors'=>'Dodavanje slike nije uspelo']);
			}
		} else {
			return Redirect::to('profile?tab=pics');
		}
	}

	public function deletePartnerLogo($id)
	{
		$p = (new Partner)->getPartnerById($id);
		$l = 'img/partners/'.$p[0]->signup_id.'/logo.png';
		if(file_exists($l) && is_file($l)) {
			unlink($l);
		}
		return Redirect::to('profile?tab=pics');
	}

	public function deletePartnerCover($id)
	{
		$p = (new Partner)->getPartnerById($id);
		$l = 'img/partners/'.$p[0]->signup_id.'/cover.jpg';
		if(file_exists($l) && is_file($l)) {
			unlink($l);
		}
		return Redirect::to('profile?tab=pics');
	}

	public function savePartnerPictures()
	{
		$file = Request::file('file');
    	$service_signup_id = Request::get('signup_id');
		$destPath = "img/partners/$service_signup_id";
		if ( $file ) {
			if (! File::exists($destPath))	File::makeDirectory($destPath);
			$destPath .= '/';
			$i = rand();
			$fileext = $file->getClientOriginalExtension();
			$upload_success = $file->move($destPath, "{$i}.{$fileext}");
			if ($upload_success) {
				if (Request::ajax()) return Response::json('success', 200);
				else return Redirect::to('profile?tab=pics');
			} else {
				if (Request::ajax()) return Response::json('error', 400);
				else return Redirect::back()->withErrors(['errors'=>'Dodavanje logoa nije uspelo']);
			}
		}
		if (Request::ajax()) return Response::json('success', 200);
		else return Redirect::to('profile?tab=pics');
	}

	public function deletePartnerPicture($id,$pic)
	{
		$p = (new Partner)->getPartnerById($id);
		$pict = 'img/partners/'.$p[0]->signup_id.'/'.$pic;
		if(file_exists($pict) && is_file($pict)) {
			unlink($pict);
		}
		return Redirect::to('profile?tab=pics');
	}

	public function setPreferredPartner($partner_id) {
		if ((int)$partner_id>0){
			if (Auth::check())
				(new User)->setPreferredPartner($partner_id);
			else
				(new Guest)->setPreferredPartnerId($partner_id);

			$partnerInfo = Partner::getShortInfo($partner_id);
			if (! is_null($partnerInfo)){
				Session::put('preferred_partner_id', $partnerInfo->partner_id);
				Session::put('preferred_partner_name',$partnerInfo->name);
				return json_encode($partnerInfo);
			}
		}
	}

	public function deletePreferredPartner(){
		if (Auth::check()) {
			DB::table('users')
				->where('user_id','=',Auth::user()->user_id)
				->update(array(
					'preferred_partner_id' => null,
					'updated_at' => date('Y-m-d H:i:s')
				));
		}
		Session::forget('preferred_partner_id');
		Session::forget('preferred_partner_name');
		return Redirect::to('/profile?tab=preferred');
	}

}