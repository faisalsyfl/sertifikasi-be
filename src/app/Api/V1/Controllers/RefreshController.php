<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use App\Traits\RestApi;

class RefreshController extends Controller
{
    use RestApi;

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     *  path="/api/v1/auth/refresh",
     *  summary="Refresh Login",
     *  tags={"Auth"},
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

    public function refresh()
    {
        $token = Auth::guard()->refresh();

        return $this->output([
            'status' => 'Successfully Refresh the token',
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL()
        ]);
    }
}