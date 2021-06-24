<?php

namespace App\Api\V1\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Controllers\Qsc2;
use App\Api\V1\Controllers\Qsc1;
use App\Models\Transaction;
use App\Models\Organization;
use App\Models\Auditi;
use App\Models\Auditor;
use App\Models\Competence;
use App\Models\Form;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class TransactionController extends Controller
{
    use RestApi;
    private $table = 'Transaction';

    protected $Qsc1;
    protected $Qsc2;
    public function __construct(Qsc1 $Qsc1, Qsc2 $Qsc2)
    {
        $this->Qsc1 = $Qsc1;
        $this->Qsc2 = $Qsc2;
    }
    /**
     * @OA\Get(
     *  path="/api/v1/qsc",
     *  summary="Get the list of transaction",
     *  tags={"Transaction"},
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
     *  path="/api/v1/qsc/{id}",
     *  summary="Get detail of transaction",
     *  tags={"Transaction"},
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
            $transaction = Transaction::findQuery($request->q);
        } else if (isset($id)) {
            $transaction = Transaction::where('id', $id);
        } else {
            $transaction = Transaction::findQuery(null);
        }
        $transaction = $transaction->where('stats', 1)->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $transaction->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($transaction);
    }


    /**
     * @OA\Post(
     *  path="/api/v1/qsc/store",
     *  summary="Save - Step 1",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="mode",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="QSC1"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="organization_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="auditi_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success"
     *  ),
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
     * @OA\Post(
     *  path="/api/v1/qsc2/store",
     *  summary="Save and Edit - Step 2",
     *  tags={"Form"},     * @OA\RequestBody(
     * @OA\JsonContent(
     *   type="object",
     *   @OA\Property(property="manajemen_mutu", type="boolean"),
     *   @OA\Property(property="manajemen_lingkungan", type="boolean"),
     *   @OA\Property(property="manajemen_keselamatan", type="boolean"),
     *   @OA\Property(property="industri_hijau", type="boolean"),
     *   @OA\Property(property="status_aplikasi_sertifikasi", type="string"),
     *   @OA\Property(property="auditi_id", type="integer"),
     *   @OA\Property(property="jumlah_personil_management", type="integer"),
     *   @OA\Property(property="jumlah_personil_administrasi", type="integer"),
     *   @OA\Property(property="jumlah_personil_part_time", type="integer"),
     *   @OA\Property(property="jumlah_personil_non_permanen", type="string"),
     *   @OA\Property(property="jumlah_personil_shift_1", type="integer"),
     *   @OA\Property(property="jumlah_personil_shift_2", type="integer"),
     *   @OA\Property(property="jumlah_personil_shift_3", type="integer"),
     *   @OA\Property(property="jumlah_personil_efektif", type="integer"),
     *   @OA\Property(property="alamat_id", type="integer"),
     *   @OA\Property(property="ruang_lingkup", type="string"),
     *   @OA\Property(property="informasi_tambahan_1", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_2", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_3", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_4", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_5", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_6", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_7", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_8", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_9", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_1_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_2_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_3_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_4_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_5_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_6_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_7_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_8_keterangan", type="string"),
     *   @OA\Property(property="informasi_tambahan_9_keterangan", type="string"),
     *   @OA\Property(property="tanda_tangan_formulir", type="integer"),
     * )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Success"
     *  ),
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

    public function store(Request $request)
    {
        switch ($request->mode) {
            case 'QSC1':
                #Section Aplikasi = 1
                $request['section'] = 1;
                $res = $this->Qsc1->store($request);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["error"]);
                return $this->output($res, 'Berhasil Menyimpan Data');

                break;
            case 'QSC2':
                #Section Aplikasi = 2
                $request['section'] = 2;
                $res = $this->Qsc2->store($request);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["error"]);
                return $this->output($res);
                break;
            case 'QSC3':
                break;
            case 'QSC4':
                break;
            case 'QSC5':
                break;
            case 'QSC6':
                break;
            case 'QSC7':
                break;
            default:
                return $this->errorRequest(422, 'Store Function Not Found');
                break;
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/qsc/list/{id}",
     *  summary="Detail - Step 1",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *      description="Fill with id_transaction",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="mode",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           default="QSC1"
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
     *  path="/api/v1/qsc2/list/{id}",
     *  summary="Detail - Step 2",
     *  tags={"Form"},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=false,
     *      description="Fill with id_transaction",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="mode",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string",
     *           default="QSC1"
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
    public function list(Request $request, $id)
    {
        switch ($request->mode) {
            case 'QSC1':
                $res = $this->Qsc1->list($request, $id);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["data"]);

                $transaction = Transaction::where('id', $id)->first();
                $final = array_merge($transaction->toArray(), ['form' => $this->serializeForm($res['data'])]);
                return $this->output($final);

                break;
            case 'QSC2':
                $res = $this->Qsc2->list($request, $id);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["data"]);

                $transaction = Transaction::where('id', $id)->first();
                $final = array_merge($transaction->toArray(), ['form' => $this->serializeForm($res['data'])]);
                return $this->output($final);
                break;
            case 'QSC3':
                break;
            case 'QSC4':
                break;
            case 'QSC5':
                break;
            case 'QSC6':
                break;
            case 'QSC7':
                break;
            default:
                return $this->errorRequest(422, 'Store Function Not Found');
                break;
        }
    }

    public function dashboard()
    {
        $org = Organization::all();
        $klien = Auditi::all();
        $auditor = Auditor::all();
        $comp = Competence::all();
        $transaction = Transaction::all();

        return $this->output(['org' => count($org), 'client' => count($klien), 'auditor' => count($auditor), 'comp' => count($comp), 'transaction' => count($transaction)]);
    }
}
