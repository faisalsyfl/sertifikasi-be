<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class OrganizationController extends Controller
{
    use RestApi;
    private $table = 'Organization';
    public function index(Request $request, $id = null)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if ($request->has('q')) {
            $organization = Organization::findQuery($request->q);
        } else if (isset($id)) {
            $organization = Organization::where('id', $id);
        } else {
            $organization = Organization::findQuery(null);
        }
        $organization = $organization->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $organization->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($organization);
    }

    public function create(Request $request)
    {
        // 'name', 'npwp', 'type', 'website', 'email', 'telp', 'address', 'city_id', 'province_id', 'postcode'

        $validate = $this->validateRequest(
            $request->all(),
            [
                'npwp' => 'required|unique:organization,npwp',
                'name' => 'required',
                'type' => 'required',
                'website' => 'required',
                'email' => 'required',
                'telp' => 'required',
                'address' => 'required',
                // 'city_id' => 'required',
                // 'province_id' => 'required',
                // 'country_id' => 'required',
                'postcode' => 'required'
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $organization = new Organization($request->all());
        $organization->save();

        return $this->output([
            'insert_id' => $organization->id,
            'data' => $organization
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
