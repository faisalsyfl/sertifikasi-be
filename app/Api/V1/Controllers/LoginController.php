<?php

namespace App\Api\V1\Controllers;

use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App\User;
use App\Traits\RestApi;

class LoginController extends Controller
{
    use RestApi;

    /**
     * Log the user in
     *
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['username', 'password']);
        $token = null;
        try {
            $user   = User::where('username', '=', $credentials['username'])->first();
            if (!$user) {
                //if user not exists
                $message = 'Username anda tidak terdaftar';
            } else {
                //if user exists
                $token  = Auth::guard()->attempt($credentials);
                if (!$token) {
                    //if password doesn't match
                    $message = 'Username / Kata Sandi yang anda masukan salah!';
                }
            }
        } catch (JWTException $e) {
            return $this->errorRequest(500);
        }

        if (!$token) {
            return $this->output([
                'status' => 0
            ], $message, 422);
        } else {
            return $this->output([
                'role'      => $user->role,
                'token'     => $token,
                'expires_in' => Auth::guard()->factory()->getTTL() * 60 * 60
            ], 'Successfully logged in');
        }
    }
}
