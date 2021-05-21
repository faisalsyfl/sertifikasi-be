<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auditi;
use App\Traits\RestApi;

class AuditiController extends Controller
{
    use RestApi;
    private $table = 'Auditi';


    public function create(Request $request)
    {

        $validate = $this->validateRequest(
            $request->all(),
            [
                'organization_id' => 'required|exists:organization,id',
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

        $auditi = new Auditi($request->all());
        $auditi->save();

        return $this->output([
            'insert_id' => $auditi->id,
            'data' => $auditi
        ], 'Success Created ' . $this->table, 200);
    }
}