<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use App\Models\Auditor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class AuditorController extends Controller
{
    use RestApi;
    private $table = 'Auditor';

    /**
     * @OA\Get(
     *  path="/api/v1/auditor",
     *  summary="Get the list of auditor",
     *  tags={"Informasi - Auditor"},
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
     *  path="/api/v1/auditor/{id}",
     *  summary="Get detail of auditor",
     *  tags={"Informasi - Auditor"},
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
            $user = Auditor::findQuery($request->q);
        } else if (isset($id)) {
            $user = Auditor::where('id', $id);
        } else {
            $user = Auditor::findQuery(null);
        }
        $user = $user->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $user->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($user);
    }
/**
     * @OA\Post(
     *  path="/api/v1/auditor",
     *  summary="Store Data auditor",
     *  tags={"Informasi - Auditor"},
     *  @OA\Parameter(
     *      name="nip",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     * @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
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
    public function create(Request $request)
    {
        // dd($request->all());
        $validate = $this->validateRequest(
            $request->all(),
            [
                'nip' => 'required|unique:users,nik',
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $user = new Auditor($request->all());
        $user->save();

        return $this->output([
            'insert_id' => $user->id,
            'data' => $user
        ], 'Success Created ' . $this->table, 200);
    }

    /**
     * @OA\Put(
     *  path="/api/v1/auditor/{id}",
     *  summary="Update Data auditor",
     *  tags={"Informasi - Auditor"},
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
     *   @OA\Property(property="nip", type="string"),
     *   @OA\Property(property="email", type="string"),
     *   @OA\Property(property="phone", type="string"),

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
    public function update(Request $request, $id)
    {
        try {
            if (isset($id) && $id) {
                $auditor = Auditor::find($id);
                if ($auditor) {
                    $auditor->update($request->all());
                } else {
                    return $this->errorRequest(422, 'Gagal Menghapus Data, Id tidak tersedia');
                }
                return $this->output('Berhasil Merubah data');
            }

            return $this->output('Id Kosong');
        } catch (\Throwable $th) {
            return $this->errorRequest(500, 'Unexpected error');
        }
    }

    /**
     * @OA\Delete(
     *  path="/api/v1/auditor/{id}",
     *  summary="Delete auditor",
     *  tags={"Informasi - Auditor"},
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
                $res = Auditor::find($id);
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
