<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class AccountController extends Controller
{
    use RestApi;
    private $table = 'Users';
    public function index(Request $request, $id = null)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if ($request->has('q')) {
            $user = User::findQuery($request->q);
        } else if (isset($id)) {
            $user = User::where('id', $id);
        } else {
            $user = User::findQuery(null);
        }
        $user = $user->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $user->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($user);
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $validate = $this->validateRequest(
            $request->all(),
            [
                'nik' => 'required|unique:users,nik',
                'name' => 'required',
                'username' => 'required',
                'email' => 'required',
                'password' => 'required',
                'phone' => 'required',
                'role' => 'required',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $user = new User($request->all());
        $user->save();

        return $this->output([
            'insert_id' => $user->id,
            'data' => $user
        ], 'Success Created ' . $this->table, 200);
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
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
}
