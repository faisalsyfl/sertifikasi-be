<?php

namespace App\Api\V1\Controllers;

use Config;
use App\Models\Transaction;
use App\Models\SectionFormValue;
use App\Models\Auditor;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class ScheduleController extends Controller
{
    use RestApi;
    private $table = 'Users';

    /**
     * @OA\Get(
     *  path="/api/v1/schedule",
     *  summary="Get the list of schedule",
     *  tags={"Informasi - Schedule"},
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
        $transaction = $transaction->orderBy('id', 'DESC')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $detail = $transaction->toArray();
        foreach($transaction as $key => $d){
            if($d->section_status[4]->status > 0){
            
            $data = SectionFormValue::join("section_form", "section_form.id", "=", "section_form_value.section_form_id")
            ->join("section_status", "section_status.id", "=", "section_form_value.section_status_id")
            ->where("section_status.transaction_id", $d->id)
            ->whereIn("section_form.key", [
                //alamat
                "alamat_klien",
                // audit
                "auditor_ids_tahap_1", "jumlah_auditor_tahap_1", "start_jadwal_tahap_1", "end_jadwal_tahap_1",
                "auditor_ids_tahap_2", "jumlah_auditor_tahap_2", "start_jadwal_tahap_2", "end_jadwal_tahap_2",
                "auditor_ids_survailen_tahap_1", "jumlah_auditor_survailen_tahap_1", "start_jadwal_survailen_tahap_1", "end_jadwal_survailen_tahap_1",
                "auditor_ids_survailen_tahap_2", "jumlah_auditor_survailen_tahap_2", "start_jadwal_survailen_tahap_2", "end_jadwal_survailen_tahap_2",
            ])
            ->get();
            foreach ($data as $item){
                if ($item->key == "auditor_ids_tahap_1" and $item->value){
                    $audit["auditors_tahap_1"] = Qsc3::get_auditor_objects(explode(",", $item->value));
                }elseif ($item->key == "auditor_ids_tahap_2" and $item->value){
                    $audit["auditors_tahap_2"] = Qsc3::get_auditor_objects(explode(",", $item->value));
                }elseif ($item->key == "auditor_ids_survailen_tahap_1" and $item->value){
                    $audit["auditors_survailen_tahap_1"] = Qsc3::get_auditor_objects(explode(",", $item->value));
                }elseif ($item->key == "auditor_ids_survailen_tahap_2" and $item->value){
                    $audit["auditors_survailen_tahap_2"] = Qsc3::get_auditor_objects(explode(",", $item->value));
                }
                $audit[$item->key] = $item->value;
            }
            $d->audit = $audit;
            }else{
                unset($transaction[$key]);
            }
        }
        return $this->output($transaction);
    }

}
