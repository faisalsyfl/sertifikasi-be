<?php

namespace App\Api\V1\Controllers\Admin;

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

class ComersController extends Controller
{
    use RestApi;
    private $role;
    public function __construct()
    {
        $this->role = 1;
    }
    public function index(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['limit' => 'numeric','page' => 'numeric']);
        if($validate)
            return $this->errorRequest(422, 'Validation Error',$validate);
            
        $limit  = $request->limit ? $request->limit : 20;
        $page   = $request->page ? $request->page : 1;
        $user   = User::where('role', '=', $this->role)->offset(($page - 1 ) * $limit)->orderBy('created_at','DESC')->limit($limit)->get();
        return $this->output($user);
    }
    public function create(Request $request)
    {
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
        if (isset($id)){        
            $user   = User::where('role', '=', $this->role)->where('id',$id)->first();
            if($user){
                return $this->output($user);
            }else{
                return $this->errorRequest(422, 'User comers tidak ditemukan');
            }
        }
        return $this->errorRequest(422, 'User comers tidak ditemukan');

    }
    public function edit(Request $request)
    {
    }
    public function update(Request $request)
    {
    }
    public function destroy(Request $request)
    {
    }
    public function activate($id){
        if (isset($id)){        
            $user   = User::where('role', '=', $this->role)->where('id',$id)->first();
            if($user){
                if($user->stats == 0){
                    $user->stats = 1;
                    $msg = 'User comers berhasil diaktifkan';
                }else{
                    $user->stats = 0;
                    $msg = 'User comers berhasil dinonaktifkan';
                }
                $user->save();
                return $this->output($user,$msg);
            }else{
                return $this->errorRequest(422, 'User comers tidak ditemukan');
            }
        }
        return $this->errorRequest(422, 'User comers tidak ditemukan');
        
    }

}
