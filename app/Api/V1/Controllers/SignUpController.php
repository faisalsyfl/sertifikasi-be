<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Hash;
use Illuminate\Support\Str;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!User::where('email','=',$request->input('email'))->exists()){
            if (!$user->save()) {
                throw new HttpException(500);
            }
            
            if (!Config::get('validation_rules.sign_up.release_token')) {
                return response()->json([
                    'status' => 'ok'
                ], 201);
            }
    
            $token = $JWTAuth->fromUser($user);
            return response()->json([
                'status' => 'ok',
                'message' => 'Sucessfully register',
                'token' => $token
            ], 201);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'User already exists'
            ], 200);
        }
    }
    public function checkUser(Request $request, JWTAuth $JWTAuth)
    {
        $user = null;
        if($request->has('username')){
            $user = User::where('username','=',$request->input('username'))->exists();
        }        
        if($request->has('email')){
            $user = User::where('email','=',$request->input('email'))->exists();
        }
        if($user){
            return response()->json([
                'status' => true,
                'message' => 'Username or Email already exists'
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Username or Email available'
            ], 200);
        }
    }
}
