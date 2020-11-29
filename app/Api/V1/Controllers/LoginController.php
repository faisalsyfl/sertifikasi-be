<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use App\User;

class LoginController extends Controller
{
    /**
     * Log the user in
     *
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['username','password']);
        try {

            $user   = User::where('username','=',$credentials['username'])->first();
            //if user not exists
            if(!$user){
                return response()
                ->json([
                    'status' => 'failed',
                    'message' => 'Username/Password not registered'
                ]);
            }
            //if exists
            $token  = Auth::guard()->attempt($credentials);
            if(!$token) {
                throw new AccessDeniedHttpException();
            }
        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'Successfully logged in',
                'role'    => $user->role,
                'token' => $token,
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]);
    }
}
