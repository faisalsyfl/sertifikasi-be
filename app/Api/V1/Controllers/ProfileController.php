<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use App\User;
use App\Traits\RestApi;

class ProfileController extends Controller
{
    use RestApi;

    public function __construct()
    {
    }
    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = Auth::user();
        if($user){
            $user->makeHidden(['email_verified_at','created_at','updated_at']);
            return $this->output($user);
        }else{
            return $this->errorRequest(422, 'Profile Not Found');
        }
    }
    public function edit(UpdateProfileRequest $request)
    {
        dd($request->all());
        // $user = Auth::user();
        // if($user){
        //     $user->makeHidden(['email_verified_at','created_at','updated_at']);
        //     return $this->output($user);
        // }else{
        //     return $this->errorRequest(422, 'Profile Not Found');
        // }
    }

}
