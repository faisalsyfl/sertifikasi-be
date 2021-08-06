<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Redirect;
use Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
	use AuthenticatesUsers;

	protected $redirectTo = '/dashboard';
	protected $guard = 'admin';

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	public function index()
	{
		return view('login');
	}

	public function getlogin(Request $request)
	{
		$username = $request->input('email');
		$password = $request->input('password');

		$rules = array(
			'email'    => 'required|email', // make sure the email is an actual email
			'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
		);
		$validator = Validator::make($request->input(), $rules);

		if ($validator->fails()) {
			return Redirect::to('auth/login')
				->withErrors($validator);
		} else {
			$userdata = array(
				'email'     => $request->input('email'),
				'password'  => $request->input('password')
			);
			if (Auth::attempt($userdata)) {
				return Redirect('dashboard');
			} else {
				return Redirect::to('auth/login');
			}
		}


		// $data = DB::table('administrator')->where('email',$username)->first();
		// if (count($data)>0) {
		// 	if (password_verify($password, $data->password)) {
		// 		echo "login";
		// 	}else{
		// 		echo "password salah";
		// 	}
		// }else{
		// 	$message = "administrator data is not registered";
		// }

	}
}