<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Traits\RestApi;

class MasterLocation extends Controller
{
    use RestApi;

    /**
     * @OA\Get(
     *  path="/api/v1/country",
     *  summary="Get the list of Country",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="q",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
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

    public function showCountry(Request $request)
    {
        try {
            $limit  = $request->has('limit') ? $request->limit : 10;
            $page   = $request->has('page') ? $request->page : 1;
            if (isset($request->q) && $request->q) {
                $country = Countries::findQuery($request->q);
            } else if (isset($request->id) && $request->id) {
                $country = Countries::where('id', $request->id);
            } else {
                $country = Countries::findQuery(null);
            }
            $country = $country->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
            $arr = $country->toArray();
            $this->pagination = array_except($arr, 'data');

            return $this->output($country);
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/state",
     *  summary="Get the list of State",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="q",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
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
    public function showState(Request $request)
    {
        try {
            $limit  = $request->has('limit') ? $request->limit : 10;
            $page   = $request->has('page') ? $request->page : 1;

            if (isset($request->q) && $request->q) {
                $state = States::with(['country'])->findQuery($request->q);
            } else {
                $state = States::with(['country']);
            }

            if ($request->id)
                $state->where('id', $request->id);
            if ($request->country_id)
                $state->where('country_id', $request->country_id);

            $state = $state->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
            $arr = $state->toArray();
            $this->pagination = array_except($arr, 'data');

            return $this->output($state);
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/city",
     *  summary="Get the list of City",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="q",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
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
    public function showCity(Request $request)
    {
        try {
            $limit  = $request->has('limit') ? $request->limit : 10;
            $page   = $request->has('page') ? $request->page : 1;

            if (isset($request->q) && $request->q) {
                $city = Cities::with(['state', 'state.country'])->findQuery($request->q);
            } else {
                $city = Cities::with(['state', 'state.country']);
            }

            if ($request->id)
                $city->where('id', $request->id);
            if ($request->country_id)
                $city->where('country_id', $request->country_id);
            if ($request->state_id)
                $city->where('state_id', $request->state_id);

            $city = $city->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
            $arr = $city->toArray();
            $this->pagination = array_except($arr, 'data');
            return $this->output($city);
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }
}