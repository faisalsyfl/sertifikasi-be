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
use App\Traits\RestApi;

class SignUpController extends Controller
{
    use RestApi;

    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!User::where('email','=',$request->input('email'))->exists() && !User::where('username','=',$request->input('username'))){
            if (!$user->save()) {
                throw new HttpException(500);
            }
            
            $token = $JWTAuth->fromUser($user);
            if(!$token){
                throw new HttpException(401);
            }
            return $this->output([
                'token' => $token,
                'username' => $user->username,
                'role'  => $user->role,
                'insert_id' => $user->id
            ], 'Pendaftaran Berhasil',200);
        }else{
            return $this->output([
                'role'  => $request->input('role'),
                'username' => $request->input('username')
            ], 'Username/Email telah terdaftar',422);
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
            return $this->output([
                'status' => false
            ], 'Username atau email telah terdaftar',422);

        }else{
            return $this->output([
                'status' => true
            ], 'Username or email tersedia',200);
        }
    }
}
