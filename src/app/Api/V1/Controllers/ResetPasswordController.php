<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ResetPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;
class ResetPasswordController extends Controller
{
    use RestApi;

    public function resetPassword(ResetPasswordRequest $request, JWTAuth $JWTAuth)
    {
        
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            if($request->password === $request->password_confirmation)
                $this->reset($user,$request->password);
            else
                return $this->errorRequest(422,'Password konfirmasi tidak sama');

        }else{
            return $this->errorRequest(422, 'Data user tidak ditemukan berdasarkan email');
        }

        return $this->output([
            'status' => 'ok',
        ],'Reset Password Berhasil');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function reset($user, $password)
    {
        $user->password = $password;
        $user->save();
    }
}