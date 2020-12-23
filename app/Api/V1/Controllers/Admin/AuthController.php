<?php

namespace App\Api\V1\Controllers\Admin;

use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App\User;
use App\Traits\RestApi;

class AuthController extends Controller
{
    use RestApi;

    public function login(Request $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['username', 'password']);
        // dd($credentials);
        $token = null;
        try {
            $user   = User::where('username', '=', $credentials['username'])->first();
            if (!$user) {
                //if user not exists
                $message = 'Username/Password not registered';
            }else{
                //if user exists
                $token  = Auth::guard()->attempt($credentials);
                if (!$token) {
                    //if password doesn't match
                    $message = 'Username or password doesn\'t match';
                }
            }
        } catch (JWTException $e) {
            return $this->errorRequest(500);
        }

        if(!$token){
            return $this->output([
                'status' => 0
            ], $message,422);
        }else{
            return $this->output([
                'role'      => $user->role,
                'token'     => $token,
                'expires_in' => Auth::guard()->factory()->getTTL() * 60 * 60
            ], 'Successfully logged in');
        }

    }
}