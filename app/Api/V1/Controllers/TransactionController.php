<?php

namespace App\Api\V1\Controllers;

use App\Models\SectionStatus;
use Validator;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Controllers\Qsc3;
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
    protected $Qsc3;
    public function __construct(Qsc1 $Qsc1, Qsc2 $Qsc2, Qsc3 $Qsc3)
    {
        $this->Qsc1 = $Qsc1;
        $this->Qsc2 = $Qsc2;
        $this->Qsc3 = $Qsc3;
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
     *  @OA\Parameter(
     *      name="auditi_status",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="seluruh/cabang"
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
     *   @OA\Property(property="mode", type="string",default="QSC2"),
     *   @OA\Property(property="section", type="integer",default="2"),
     *   @OA\Property(property="transaction_id", type="integer"),
     *   @OA\Property(property="section_status_id", type="integer"),
     *   @OA\Property(property="manajemen_mutu", type="boolean"),
     *   @OA\Property(property="manajemen_lingkungan", type="boolean"),
     *   @OA\Property(property="manajemen_keselamatan", type="boolean"),
     *   @OA\Property(property="industri_hijau", type="boolean"),
     *   @OA\Property(property="status_aplikasi_sertifikasi", type="string",default="SERTIFIKASI_AWAL"),
     *   @OA\Property(property="auditi_id", type="integer"),
     *   @OA\Property(property="jumlah_personil_management", type="string"),
     *   @OA\Property(property="jumlah_personil_administrasi", type="string"),
     *   @OA\Property(property="jumlah_personil_part_time", type="string"),
     *   @OA\Property(property="jumlah_personil_non_permanen", type="string"),
     *   @OA\Property(property="jumlah_personil_shift_1", type="string"),
     *   @OA\Property(property="jumlah_personil_shift_2", type="string"),
     *   @OA\Property(property="jumlah_personil_shift_3", type="string"),
     *   @OA\Property(property="jumlah_personil_efektif", type="string"),
     *   @OA\Property(property="alamat", type="string"),
     *   @OA\Property(property="lokasi_id", type="string", default="1,2,3"),
     *   @OA\Property(property="multilokasi", type="boolean"),
     *   @OA\Property(property="ruang_lingkup", type="string"),
     *   @OA\Property(property="informasi_tambahan_1", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_2", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_3", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_4", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_5", type="boolean"),
     *   @OA\Property(property="informasi_tambahan_6", type="boolean"),
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

    /**
     * @OA\Post(
     *  path="/api/v1/qsc3/store",
     *  summary="Save and Edit - Step 3",
     *  tags={"Form"},
     * @OA\RequestBody(
     * @OA\JsonContent(
     *   type="object",
     *   @OA\Property(property="mode", type="QSC3"),
     *   @OA\Property(property="section", type="integer", default="3"),
     *   @OA\Property(property="transaction_id", type="integer"),
     *   @OA\Property(property="section_status_id", type="integer"),
     *   @OA\Property(property="nama_klien", type="string"),
     *   @OA\Property(property="status_aplikasi_sertifikasi", type="string"),
     *   @OA\Property(property="manajemen_mutu", type="boolean"),
     *   @OA\Property(property="manajemen_lingkungan", type="boolean"),
     *   @OA\Property(property="manajemen_keselamatan", type="boolean"),
     *   @OA\Property(property="industri_hijau", type="boolean"),
     *   @OA\Property(property="audit_single", type="boolean"),
     *   @OA\Property(property="audit_joint", type="boolean"),
     *   @OA\Property(property="audit_combination", type="boolean"),
     *   @OA\Property(property="audit_integration", type="boolean"),
     *   @OA\Property(property="lingkup", type="string"),
     *   @OA\Property(property="sektor_ea", type="string"),
     *   @OA\Property(property="sektor_nace", type="string"),
     *   @OA\Property(property="akreditasi_lingkup_kan", type="boolean"),
     *   @OA\Property(property="personil_kompeten", type="boolean"),
     *   @OA\Property(property="konflik_kepentingan", type="boolean"),
     *   @OA\Property(property="penjelasan_solusi", type="string"),
     *   @OA\Property(property="lokasi_id", type="string"),
     *   @OA\Property(property="multi_site", type="boolean"),
     *   @OA\Property(property="audit_shift_1", type="boolean"),
     *   @OA\Property(property="audit_shift_2", type="boolean"),
     *   @OA\Property(property="audit_shift_3", type="boolean"),
     *   @OA\Property(property="risiko_iso_9001", type="string"),
     *   @OA\Property(property="kompleksitas_iso_14001", type="string"),
     *   @OA\Property(property="kompleksitas_iso_45001", type="string"),
     *   @OA\Property(property="jumlah_personil_management", type="integer"),
     *   @OA\Property(property="jumlah_personil_administrasi", type="integer"),
     *   @OA\Property(property="jumlah_personil_part_time", type="integer"),
     *   @OA\Property(property="jumlah_personil_non_permanen", type="integer"),
     *   @OA\Property(property="jumlah_personil_shift_1", type="integer"),
     *   @OA\Property(property="jumlah_personil_shift_2", type="integer"),
     *   @OA\Property(property="jumlah_personil_shift_3", type="integer"),
     *   @OA\Property(property="keterangan_shift", type="string"),
     *   @OA\Property(property="waktu_audit_iso_9001", type="integer"),
     *   @OA\Property(property="waktu_audit_iso_14001", type="integer"),
     *   @OA\Property(property="waktu_audit_iso_45001", type="integer"),
     *   @OA\Property(property="kompleksitas_rendah_iso_9001", type="boolean"),
     *   @OA\Property(property="lokasi_kecil", type="boolean"),
     *   @OA\Property(property="kematangan_sistem_manajemen", type="boolean"),
     *   @OA\Property(property="pernah_diaudit_b4t", type="boolean"),
     *   @OA\Property(property="tersertifikasi_pihak_ketiga", type="boolean"),
     *   @OA\Property(property="proses_otomasi_tinggi", type="boolean"),
     *   @OA\Property(property="staff_luar_kantor", type="boolean"),
     *   @OA\Property(property="pengurangan_lainnya", type="string"),
     *   @OA\Property(property="persentasi_pengurangan", type="integer"),
     *   @OA\Property(property="resiko_tinggi_iso_9001", type="boolean"),
     *   @OA\Property(property="kegiatan_sama_beda_lokasi", type="boolean"),
     *   @OA\Property(property="bahasa_staf_lebih_dari_satu", type="boolean"),
     *   @OA\Property(property="lokasi_luas", type="boolean"),
     *   @OA\Property(property="peraturan_ketat", type="boolean"),
     *   @OA\Property(property="proses_kompleks", type="boolean"),
     *   @OA\Property(property="lokasi_non_permanen", type="boolean"),
     *   @OA\Property(property="penambahan_lainnya", type="string"),
     *   @OA\Property(property="persentasi_penambahan", type="integer"),
     *   @OA\Property(property="total_penyesuaian", type="integer"),
     *   @OA\Property(property="summary_waktu_audit_iso_9001", type="integer"),
     *   @OA\Property(property="summary_waktu_audit_iso_14001", type="integer"),
     *   @OA\Property(property="summary_waktu_audit_iso_45001", type="integer"),
     *   @OA\Property(property="summary_total", type="integer"),
     *   @OA\Property(property="resertifikasi_iso_9001", type="integer"),
     *   @OA\Property(property="resertifikasi_iso_14001", type="integer"),
     *   @OA\Property(property="resertifikasi_iso_45001", type="integer"),
     *   @OA\Property(property="resertifikasi_total", type="integer"),
     *   @OA\Property(property="menerapkan_integrasi", type="boolean"),
     *   @OA\Property(property="sistem_diintegrasi", type="string"),
     *   @OA\Property(property="jumlah_sistem", type="integer"),
     *   @OA\Property(property="integrasi_dokumen", type="boolean"),
     *   @OA\Property(property="integrasi_tinjauan_manajemen", type="boolean"),
     *   @OA\Property(property="integrasi_internal_audit", type="boolean"),
     *   @OA\Property(property="integrasi_kebijakan", type="boolean"),
     *   @OA\Property(property="integrasi_sistem_proses", type="boolean"),
     *   @OA\Property(property="persentase_tingkat_integrasi", type="integer"),
     *   @OA\Property(property="jumlah_auditor", type="integer"),
     *   @OA\Property(property="auditor_ids", type="string", default="1,2,3"),
     *   @OA\Property(property="kemampuan_auditor", type="integer"),
     *   @OA\Property(property="pengurangan_waktu_integrasi", type="integer"),
     *   @OA\Property(property="final_total_waktu_audit", type="integer"),
     *   @OA\Property(property="awal_tahap_1_perhitungan", type="integer"),
     *   @OA\Property(property="awal_tahap_1_penyesuaian", type="integer"),
     *   @OA\Property(property="awal_tahap_2_perhitungan", type="integer"),
     *   @OA\Property(property="awal_tahap_2_penyesuaian", type="integer"),
     *   @OA\Property(property="resertifikasi_tahap_1_perhitungan", type="integer"),
     *   @OA\Property(property="resertifikasi_tahap_1_penyesuaian", type="integer"),
     *   @OA\Property(property="resertifikasi_tahap_2_perhitungan", type="integer"),
     *   @OA\Property(property="resertifikasi_tahap_2_penyesuaian", type="integer"),
     *   @OA\Property(property="survailen_tahap_1_perhitungan", type="integer"),
     *   @OA\Property(property="survailen_tahap_1_penyesuaian", type="integer"),
     *   @OA\Property(property="survailen_tahap_2_perhitungan", type="integer"),
     *   @OA\Property(property="survailen_tahap_2_penyesuaian", type="integer"),
     *   @OA\Property(property="justifikasi_waktu_audit", type="integer"),
     *   @OA\Property(property="aplikasi_sertifikasi", type="boolean"),
     *   @OA\Property(property="catatan_aplikasi_ditolak", type="string"),
     *   @OA\Property(property="nomor_sertifikasi", type="string"),
     *   @OA\Property(property="dikaji_oleh_1", type="string"),
     *   @OA\Property(property="dikaji_oleh_2", type="string"),
     *   @OA\Property(property="disetujui_oleh", type="string"),
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
                #Section Aplikasi = 3
                $request['section'] = 3;
                $res = $this->Qsc3->store($request);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["error"]);
                return $this->output($res);
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
     *           default="QSC2"
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
     *  path="/api/v1/qsc3/list/{id}",
     *  summary="Detail - Step 3",
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
     *           default="QSC3"
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
                $res = $this->Qsc3->list($request, $id);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["data"]);

                $transaction = Transaction::where('id', $id)->first();
                $final = array_merge($transaction->toArray(), ['form' => $this->serializeForm($res['data'])]);
                return $this->output($final);
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

    /**
     * @OA\Put(
     *  path="/api/v1/qsc/status/{id}",
     *  summary="Update Section Status",
     *  tags={"Form"},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="",
     *      @OA\Schema(
     *           type="integer",
     *           format="int64"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="status",
     *      in="query",
     *      required=true,
     *      description="Status Number (0-3)",
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
    public function setStatus(Request $request, $id)
    {
        $validate = $this->validateRequest(
            $request->all(),
            [
                'status' => 'required',
            ]
        );
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $status = $request->input("status");

        if($id and $status){
            $section_status = SectionStatus::find($id);

            if($section_status){
                $section_status->update([
                    "status" => $status
                ]);

                return $this->output('Berhasil Merubah data');
            }else{
                return $this->errorRequest(422, 'Gagal Memperbarui Data, Section Status tidak ditemukan');
            }
        }else{
            return $this->errorRequest(422, 'Gagal Memperbarui Data, Id tidak tersedia');
        }
    }
}
