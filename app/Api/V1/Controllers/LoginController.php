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

    /**
     * @OA\Post(
     *  path="/api/v1/auth/login",
     *  summary="Auth Login",
     *  tags={"Auth"},
     *  @OA\Parameter(
     *      name="username",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="admin"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="b4t"
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success"
     *  ),
     *  @OA\Response(response=201,description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden"),
     * )
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