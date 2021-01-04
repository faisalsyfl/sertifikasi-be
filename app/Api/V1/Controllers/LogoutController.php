<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\Traits\RestApi;

class LogoutController extends Controller
{
    use RestApi;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard()->logout();

        return $this->output([
            'success' => true 
        ],'Anda berhasil keluar');
    }
}
