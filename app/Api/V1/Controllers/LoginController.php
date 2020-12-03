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
        try {

            $user   = User::where('username', '=', $credentials['username'])->first();
            //if user not exists
            if (!$user) {
                return $this->output([
                    'status' => 'failed'
                ], 'Username/Password not registered');
            }

            //if exists
            $token  = Auth::guard()->attempt($credentials);
            if (!$token) {
                return $this->errorRequest(403);
            }
        } catch (JWTException $e) {
            return $this->errorRequest(500);
        }

        return $this->output([
            'role'    => $user->role,
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60 * 60
        ], 'Successfully logged in');
    }
}