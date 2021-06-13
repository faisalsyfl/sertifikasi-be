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
     *  tags={"Transaction - Form"},
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
        $transaction = $transaction->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $transaction->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($transaction);
    }

    public function store(Request $request)
    {
        switch ($request->mode) {
            case 'QSC1':
                #Section Aplikasi = 1
                $request['section'] = 1;
                $res = $this->Qsc1->store($request);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["error"]);
                return $this->output($res);

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

    public function list(Request $request)
    {
        // dd($request->all());
        switch ($request->mode) {
            case 'QSC1':

                break;
            case 'QSC2':
                // dd($request->all());
                $request['transaction_id'] = 1;
                $request['section_id'] = 2;
                $res = $this->Qsc2->list($request);
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

    public function qsc1(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['organization_id' => 'required|exists:organization,id', 'auditi_id' => 'required|exists:auditi,id']);
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $transaction = new Transaction($request->all());
        $transaction->status = 1;
        $transaction->code   = 'SC';
        $transaction->save();

        $form = new Form(['transaction_id' => $transaction->id]);
        $form->save();

        return $this->output($transaction);
    }
    public function qsc2(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['transaction_id' => 'required']);
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        // dd($request->exists('Cname'));
        $form = Form::where('transaction_id', $request->transaction_id);
        $input = $request->all();
        $form->update($input);

        $form = Form::where('transaction_id', $request->transaction_id)->first();
        // $form->save();
        // $form->get();
        return $this->output($form);
    }
}
