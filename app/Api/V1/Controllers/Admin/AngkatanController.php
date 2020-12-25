<?php

namespace App\Api\V1\Controllers\Admin;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Models\angkatanModel;
use App\Traits\RestApi;

class AngkatanController extends Controller
{
    use RestApi;
    public function __construct()
    {}
    public function index(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['limit' => 'numeric','page' => 'numeric']);
        if($validate)
            return $this->errorRequest(422, 'Validation Error',$validate);
            
        $limit  = $request->limit ? $request->limit : 10;
        $page   = $request->page ? $request->page : 1;
        $angkatan   = angkatanModel::offset(($page - 1 ) * $limit)->limit($limit)->get();
        return $this->output($angkatan);
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
                return $this->errorRequest(422, 'User Not Found');
            }
        }
        return $this->errorRequest(422, 'User Not Found');

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
    public function activate(Request $request){
        $validate = $this->validateRequest($request->all(), ['limit' => 'numeric','page' => 'numeric']);
        
    }

}
