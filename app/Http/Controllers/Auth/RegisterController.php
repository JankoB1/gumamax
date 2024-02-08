<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Delmax\User\UserCustomer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/gume';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['customer_type_id'] == 1) {
            return Validator::make($data, [
                'username' => ['required', 'string', 'max:255', 'unique:ApiDB.user'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:ApiDB.user'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'family_name' => ['required', 'string', 'max:255'],
                'first_name' => ['required', 'string', 'max:255'],
                'tel' => ['required'],
                'customer_type_id' => ['required']
            ]);
        }
        else {
            return Validator::make($data, [
                'username' => ['required', 'string', 'max:255', 'unique:ApiDB.user'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:ApiDB.user'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'family_name' => ['required', 'string', 'max:255'],
                'first_name' => ['required', 'string', 'max:255'],
                'tel' => ['required'],
                'customer_type_id' => ['required'],
                'company_name' => ['required', 'string', 'max:255'],
                'tax_identification_number' => ['required', 'numeric']
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'last_name' => $data['family_name'],
            'first_name' => $data['first_name'],
            'name' => $data['first_name'].' '.$data['family_name'],
            'phone_number' => $data['tel']
        ]);

        $user->addAsCustomer(
            UserCustomer::make([
            'customer_type_id' => $data['customer_type_id'],
            'company_name' => $data['company_name'],
            'tax_identification_number' => $data['tax_identification_number'],
        ])
    );

        return $user;
    }
}
