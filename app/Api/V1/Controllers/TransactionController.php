<?php

namespace App\Api\V1\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Controllers\Qsc2;
use App\Models\Transaction;
use App\Models\Form;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class TransactionController extends Controller
{
    use RestApi;
    private $table = 'Transaction';

    protected $Qsc2;
    public function __construct(Qsc2 $Qsc2)
    {
        $this->Qsc2 = $Qsc2;
    }

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
        // dd($request);
        switch ($request->mode) {
            case 'QSC1':
                break;
            case 'QSC2':
                $res = $this->Qsc2->store($request);
                if (!$res["status"])
                    return $this->errorRequest(422, 'Validation Error', $res["error"]);


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
