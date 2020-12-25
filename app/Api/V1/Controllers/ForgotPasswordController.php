<?php

namespace App\Api\V1\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Api\V1\Requests\ForgotPasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Mail\SendMail;
use App\Traits\RestApi;
use App\Models\mailTokenModel;



class ForgotPasswordController extends Controller
{
    use RestApi;

    public function __construct()
    { }
    public function sendResetEmail(ForgotPasswordRequest $request)
    {
        $user = User::where('email', '=', $request->input('email'))->first();
        if(!$user) {
            throw new NotFoundHttpException();
        }
        //Inactive current token
        $current = mailTokenModel::where('email','=',$request->input('email'))->first();
        if($current){
            $current->active_token = 0;
            $current->save();
        }

        // Manual Mail
        $token = rand(100000,999999);
        $mailToken = new mailTokenModel(['email' => $request->input('email'), 'token' => $token]);
        $mailToken->save();
        $sendMail = Mail::to($request->input('email'))->send(new SendMail($request->input('username'), $token));
        if(Mail::failures()){
            $mailToken->active_token = 0;
            $mailToken->save();
            return $this->errorRequest(422,'Gagal Mengirimkan Kode');
        }   
        return $this->output($mailToken,'Kode Berhasil Dikirim');
        
    }

    public function verifyToken(Request $request){
        $validate = $this->validateRequest($request->all(), ['email' => 'required|email','token' => 'numeric']);
        if($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);
        
        $mailToken = mailTokenModel::where('email',$request->input('email'))->where('token',$request->input('token'))->first();
        if(!$mailToken){
            return $this->errorRequest(422,'Kode gagal di verifikasi');
        }
        return $this->output(['verify' => 1],'Kode berhasil di verifikasi');
        
    }
    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker();
    }
}
