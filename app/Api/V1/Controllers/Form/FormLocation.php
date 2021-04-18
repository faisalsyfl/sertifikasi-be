<?php

namespace App\Api\V1\Controllers\Form;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormLocation as locationModel;
use App\Traits\RestApi;
use App\Api\V1\Requests\RuleFormLocation;

class FormLocation extends Controller
{
    use RestApi;

    /**
     * @OA\Post(
     *  path="/api/v1/form/location",
     *  summary="Store Location Endpoint",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="location_type",
     *      in="query",
     *      required=true,
     *      description="[KEGIATAN_UTAMA,KEGIATAN_LAIN,KEGIATAN_NON_PERMANEN]",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ), 
     *  @OA\Parameter(
     *      name="location",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ), 
     * @OA\Parameter(
     *      name="form_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
     * @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),   
     * @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),   
     *  @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="postcode",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Response(response=200,description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  @OA\Response(response=201,description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden"),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function storeFormLocation(RuleFormLocation $request)
    {
        $location = new locationModel($request->all());
        $location->save();

        return $this->output([
            'id' => $location->id,
            'data' => $location
        ], 'Success Created', 200);
    }
}