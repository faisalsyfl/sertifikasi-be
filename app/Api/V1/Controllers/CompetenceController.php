<?php

namespace App\Api\V1\Controllers;

use App\Models\Competence;
use Config;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class CompetenceController extends Controller
{
    use RestApi;
    private $table = 'Competence';

    /**
     * @OA\Get(
     *  path="/api/v1/competence",
     *  summary="Get the list of competency",
     *  tags={"Informasi - Competence"},
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
     *  path="/api/v1/competence/{id}",
     *  summary="Get detail of competency",
     *  tags={"Informasi - Competence"},
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
            $competency = Competence::findQuery($request->q);
        } else if (isset($id)) {
            $competency = Competence::where('id', $id);
        } else {
            $competency = Competence::findQuery(null);
        }
        $competency = $competency->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $competency->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($competency);
    }

    /**
     * @OA\Post(
     *  path="/api/v1/competence",
     *  summary="Store Data competency",
     *  tags={"Informasi - Competence"},
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
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
                'name' => 'required',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $competency = new Competence($request->all());
        $competency->save();

        return $this->output([
            'insert_id' => $competency->id,
            'data' => $competency
        ], 'Success Created ' . $this->table, 200);
    }

    /**
     * @OA\Put(
     *  path="/api/v1/competence/{id}",
     *  summary="Update Data competency",
     *  tags={"Informasi - Competence"},
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
                $competency = Competence::find($id);
                if ($competency) {
                    $competency->update($request->all());
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
     *  path="/api/v1/competence/{id}",
     *  summary="Delete competency",
     *  tags={"Informasi - Competence"},
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
                $res = Competence::find($id);
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
