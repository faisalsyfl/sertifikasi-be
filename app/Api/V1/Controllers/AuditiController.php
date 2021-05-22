<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auditi;
use App\Traits\RestApi;
use App\Api\V1\Requests\RuleAuditi;

class AuditiController extends Controller
{
    use RestApi;
    private $table = 'Auditi';

    /**
     * @OA\Get(
     *  path="/api/v1/auditi",
     *  summary="Get the list of auditi",
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
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
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

    /**
     * @OA\Get(
     *  path="/api/v1/auditi/{id}",
     *  summary="Get detail of auditi",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
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
    public function index(Request $request, $id = null)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if ($request->has('q')) {
            $Auditi = Auditi::with(['organization', 'country', 'city', 'state'])->findQuery($request->q);
        } else if (isset($id)) {
            $Auditi = Auditi::with(['organization', 'country', 'city', 'state'])->where('id', $id);
        } else {
            $Auditi = Auditi::with(['organization', 'country', 'city', 'state']);
        }
        $Auditi = $Auditi->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $Auditi->toArray();
        $this->pagination = array_except($arr, 'data');

        if (isset($id))
            $Auditi = $Auditi->first();
        return $this->output($Auditi);
    }

    /**
     * @OA\Post(
     *  path="/api/v1/auditi",
     *  summary="Store Data Auditi",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="organization_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="website",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="telp",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="postcode",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number",
     *           minimum=5,
     *           maximum=5,
     *      )
     *   ),
     * @OA\Parameter(
     *      name="country_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="state_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     * @OA\Parameter(
     *      name="city_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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
    public function create(RuleAuditi $request)
    {
        $auditi = new Auditi($request->all());
        $auditi->save();

        return $this->output([
            'insert_id' => $auditi->id,
            'data' => $auditi
        ], 'Success Created ' . $this->table, 200);
    }

    /**
     * @OA\Put(
     *  path="/api/v1/auditi/{id}",
     *  summary="Update Data Auditi",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="",
     *      @OA\Schema(
     *           type="integer",
     *           format="int64"
     *      )
     *   ),
     * @OA\RequestBody(
     * @OA\JsonContent(
     *   type="object",
     *   @OA\Property(property="name", type="string"),
     *   @OA\Property(property="organization_id", type="number"),
     *   @OA\Property(property="type", type="string"),
     *   @OA\Property(property="website", type="string"),
     *   @OA\Property(property="email", type="string"),
     *   @OA\Property(property="telp", type="string"),
     *   @OA\Property(property="address", type="string"),
     *   @OA\Property(property="postcode", type="number"),
     *   @OA\Property(property="city_id", type="number"),
     *   @OA\Property(property="state_id", type="number"),
     *   @OA\Property(property="country_id", type="number"),
     * )
     * ),
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
    public function update(RuleAuditi $request, $id)
    {
        try {
            if (isset($id) && $id) {
                $org = Auditi::find($id);
                if ($org) {
                    $org->update($request->all());
                } else {
                    return $this->errorRequest(422, 'Gagal Menghapus Data, Id tidak tersedia');
                }
                return $this->output('Berhasil Merubah data');
            }

            return $this->output('ID Kosong');
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }

    /**
     * @OA\Delete(
     *  path="/api/v1/auditi/{id}",
     *  summary="Delete Auditi",
     *  tags={"Master"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="",
     *      @OA\Schema(
     *           type="integer",
     *           format="int64"
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
    public function destroy($id)
    {
        try {
            if (isset($id) && $id) {
                $res = Auditi::find($id);
                if ($res) {
                    $res->delete();
                } else {
                    return $this->errorRequest(422, 'Gagal Menghapus Data, Id tidak tersedia');
                }
                return $this->output('Berhasil menghapus data');
            }

            return $this->output('ID Kosong');
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }
}