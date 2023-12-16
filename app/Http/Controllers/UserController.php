<?php namespace App\Http\Controllers;


use Gumamax\Users\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends DmxBaseController {
    private $regexNameRules = "/^[a-zA-ZабвгдђежзијклљмнњопрстћуфхцчџшАБВГДЂЕЖЗИЈКЛЉМНЊОПРСТЋУФХЦЧЏШàáâäãåąćęèéêëìíîïłńòóôöõøùúûüÿýżźñçčćđšžÀÁÂÄÃÅĄĆĘÈÉÊËÌÍÎÏŁŃÒÓÔÖÕØÙÚÛÜŸÝŻŹÑßÇŒÆČĆĐŠŽ∂ð ,.'-]+$/u";
	/**
	 * @var
	 */
	private $userRepository;

	public function __construct(UserRepository $userRepository){

		parent::__construct();
		$this->userRepository = $userRepository;

	}
    public function getAll(Request $request)
    {
		if ($request->ajax())
		{
    		return Response::json(User::getAll());
		} else {
    		return User::getAll();

		}
    }

	public function allGmxUsers(){
		return $this->userRepository->gmxUsers();
	}

	public function adminIndex($role=null){

		return view('admin.user.index', compact('role'));

	}

	public function apiDatatablesUsersByRole($role){

		return $this->userRepository->apiDatatables($role);

	}

	public function apiSelect2Users(Request $request){

		$data = $this->userRepository->lookupQuery($request);

		return $this->respond($data);

	}

}