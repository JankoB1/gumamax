<?php namespace App\Http\Controllers;

use Request;

class HomeTestController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth.basic');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home');
	}


	public function home()
	{
		return view('home.index');
	}

	public function account()
	{
		return view('account.personal.show');
	}

	public function accountp()
	{
		return view('account.partner.show');
	}

	public function login()
	{
		return view('account.login');
	}

	public function register()
	{
		return view('account.register');
	}

	public function reset()
	{
		return view('account.reset');
	}

	public function testlang(){
        return
		//setlocale(LC_ALL, "en_US.UTF-8");
		$num = 1234.5;
		echo $num;
	}

}
