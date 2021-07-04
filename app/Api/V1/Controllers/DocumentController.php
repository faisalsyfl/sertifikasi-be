<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    use RestApi;
    private $table = 'document';

    /**
     * @OA\Get(
     *  path="/api/v1/document",
     *  summary="Get the list of document",
     *  tags={"Informasi - Document"},
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
     *  path="/api/v1/document/{id}",
     *  summary="Get detail of document",
     *  tags={"Informasi - Document"},
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
            $user = Document::findQuery($request->q);
        } else if (isset($id)) {
            $user = Document::where('id', $id);
        } else {
            $user = Document::findQuery(null);
        }
        $user = $user->orderBy('id', 'DESC')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $user->toArray();
        foreach ($arr['data'] as $key => $value) {
            $arr['data'][$key]['file_url'] = asset('storage/document/' .  $value['file_hash']);
        }
        $this->pagination = array_except($arr, 'data');

        return $this->output($arr);
    }
    /**
     * @OA\Post(
     *  path="/api/v1/document",
     *  summary="Store Data document",
     *  tags={"Informasi - Document"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file","name","title","code","type"},
     *                 @OA\Property(
     *                     description="name",
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="title",
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="code",
     *                     property="code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="type",
     *                     property="type",
     *                     description="[INTERNAL,EXTERNAL]",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="file",
     *                     type="file",
     *                     format="file",
     *                 )
     *             )
     *         )
     *     ),
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
        $validate = $this->validateRequest(
            $request->all(),
            [
                'name' => 'required',
                'title' => 'required',
                // 'code' => 'required',
                'type' => 'required',
                // 'file_type' => 'required',
                // 'file_size' => 'required',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $insert = $request->all();
        if ($request->file('file')) {
            $file = $request->file('file');
            $file_hash                  = 'document_' . $this->hash_filename();
            $file_info['file_hash']     = str_replace(' ', '', trim($file_hash . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)));
            $save = Storage::disk('local')->put('public/document/' . $file_info['file_hash'], file_get_contents($file));
            if ($save) {
                $insert['file_hash'] = $file_info['file_hash'];
                $insert['file_type'] = $file->getClientOriginalExtension();
                $insert['file_size'] = $file->getSize();
                $insert['created_by'] = Auth::user()->id;
                unset($insert['file']);
            }
        }
        if ($save) {
            $doc = new Document($insert);
            $doc->save();
        } else {
            $this->errorRequest(422, 'File Upload Error');
        }

        return $this->output([
            'insert_id' => $doc->id,
            'data' => $doc
        ], 'Success Created ' . $this->table, 200);
    }

    /**
     * @OA\Put(
     *  path="/api/v1/document/{id}",
     *  summary="Update Data document",
     *  tags={"Informasi - Document"},
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
     *  @OA\Parameter(
     *      name="file",
     *      in="path",
     *      description="",
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
     * @OA\RequestBody(
     * @OA\JsonContent(
     *   type="object",
     *   @OA\Property(property="name", type="string"),
     *   @OA\Property(property="title", type="string"),
     *   @OA\Property(property="code", type="string"),
     *   @OA\Property(property="type", type="string"),
     *   @OA\Property(property="status", type="string"),

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
                $auditor = Document::find($id);
                $insert = $request->all();
                if ($request->file('file')) {
                    $file = $request->file('file');
                    $file_hash                  = 'document_' . $this->hash_filename();
                    $file_info['file_hash']     = str_replace(' ', '', trim($file_hash . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)));
                    $save = Storage::disk('local')->put('public/document/' . $file_info['file_hash'], file_get_contents($file));
                    if ($save) {
                        $insert['file_hash'] = $file_info['file_hash'];
                        $insert['file_type'] = $file->getClientOriginalExtension();
                        $insert['file_size'] = $file->getSize();
                        $insert['created_by'] = Auth::user()->id;
                        unset($insert['file']);
                    }
                }
                if ($auditor) {
                    $auditor->update($insert);
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
     *  path="/api/v1/document/{id}",
     *  summary="Delete document",
     *  tags={"Informasi - Document"},
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
                $res = Document::find($id);
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
