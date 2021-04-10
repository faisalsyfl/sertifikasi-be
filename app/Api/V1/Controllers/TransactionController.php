<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Form;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class TransactionController extends Controller
{
    use RestApi;
    private $table = 'Transaction';

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

    public function qsc1(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['organization_id' => 'required|exists:organization,id', 'auditi_id' => 'required|exists:auditi,id']);
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $transaction = new Transaction($request->all());
        $transaction->status = 1;
        $transaction->code   = 'SC';
        $transaction->save();

        return $this->output($transaction);
    }
    public function qsc2(Request $request)
    {
        $validate = $this->validateRequest($request->all(), ['A1' => 'required', 'B1' => 'required']);
        if ($validate)
            return $this->errorRequest(422, 'Validation Error', $validate);

        $form = new Form($request->all());
        $form->save();

        return $this->output($form);
    }
}
