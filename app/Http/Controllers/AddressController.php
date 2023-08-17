<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Delmax\Addresses\Address;
use Delmax\Addresses\SaveAddressRequest;
use Yajra\Datatables\Datatables;

class AddressController extends  DmxBaseController {

	/**
	 * @return mixed
	 */
	public function datatablesApi()
	{
		$data = Address::select(['id', 'address', 'city_name'])
			->where('addressable_type', 'App\Models\User')
			->where('addressable_id', auth()->user()->user_id);

		$dt = datatables()::of($data);
		$dt->addColumn('checkbox',
			function ($model) {
				return '<input type="checkbox" name="ids[]" class="row_check" value="' . $model->id . '">';
			}
		);
		return $dt->make(true);
	}

	public function userAddressesApi()
	{
		return auth()->user()->addresses()->with('city')->get();
	}
	/**
	 * User Address Methods
	 * @param Request $request
	 * @return \Illuminate\View\View
	 */
	public function create(Request $request){

		$formActionRoute = route('address.store');

		$formMethod      = 'POST';

		$model = Address::make($request->all());

		$model->recipient = auth()->user()->fullName();

		$model->email = auth()->user()->email;

		$model->phone_number = auth()->user()->phone_number;


		return view('account.address.edit', compact('id', 'model', 'formActionRoute', 'formMethod'));
	}

	public function edit(Request $request, $id){

		$addressable_type = $request->get('addressable_type');

		$addressable_id   = $request->get('addressable_id');

		$model = Address::find($id);

		if(!$model){

			abort(404);

		}

		$formActionRoute = route('address.update', [$id]);

		$formMethod = 'PUT';

		return view('account.address.edit', compact('id', 'model', 'formActionRoute', 'formMethod', 'addressable_type','addressable_id'));

	}

	public function store(SaveAddressRequest $request){

		$model = Address::make($request->all());

		$model->save();

		flash()->success('Adresa', 'Zapis je kreiran');

		return 'success';

	}


	public function update(SaveAddressRequest $request, $id){

		$model = Address::find($id);

		if (!$model) {

			abort(404);

		}

		$model->fill($request->all());

		$model->save();

		flash()->success('Adresa', 'Zapis je izmenjen');

		return 'success';

	}

	public function destroy(Request $request, $id){
		if (!isset($id)) {

			abort(400);

		}

		if (is_array($id)) {

			Address::destroy($id);

			return 'success';

		}

		$model = Address::find($id);

		if (!$model) {
			abort(404);
		}

		$model->delete();

		flash()->success('Address', 'Record has been deleted');

		return 'success';
	}

}
