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
    public function refresh()
    {
        $token = Auth::guard()->refresh();

        return $this->output([
            'status' => 'Successfully Refresh the token',
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }
}
