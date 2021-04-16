<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Dingo\Api\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\RestApi;

class MasterLocation extends Controller
{
    use RestApi;
    public function showCountry(Request $request)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if (isset($request->q) && $request->q) {
            $country = Countries::findQuery($request->q);
        } else if (isset($request->id) && $request->id) {
            $country = Countries::where('id', $request->id);
        } else {
            $country = Countries::findQuery(null);
        }
        $country = $country->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $country->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($country);
    }

    public function showState(Request $request)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if (isset($request->q) && $request->q) {
            $state = States::with(['country'])->findQuery($request->q);
        } else if (isset($request->id) && $request->id) {
            $state = States::with(['country'])->where('id', $request->id);
        } else {
            $state = States::with(['country'])->findQuery(null);
        }
        $state = $state->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $state->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($state);
    }

    public function showCity(Request $request)
    {
        $limit  = $request->has('limit') ? $request->limit : 10;
        $page   = $request->has('page') ? $request->page : 1;
        if (isset($request->id) && $request->q) {
            $city = Cities::with(['state', 'state.country'])->findQuery($request->q);
        } else if (isset($request->id) && $request->id) {
            $city = Cities::with(['state', 'state.country'])->where('id', $request->id);
        } else {
            $city = Cities::with(['state', 'state.country'])->findQuery(null);
        }
        $city = $city->orderBy('updated_at')->offset(($page - 1) * $limit)->limit($limit)->paginate($limit);
        $arr = $city->toArray();
        $this->pagination = array_except($arr, 'data');

        return $this->output($city);
    }
}