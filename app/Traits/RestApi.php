<?php

namespace App\Traits;

use Illuminate\Http\Response;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

if (!defined('LARAVEL_START')) define('LARAVEL_START', microtime(true));

trait RestApi
{
    // Set default isArray false
    protected $isArray = false;
    // Set default pagination false
    protected $paginaton = false;
    /**
     * Generate output data
     * @param  array  $data
     * @return array Response
     */
    function output($data, $success_response = 'Data Found', $success_code = 200)
    {
        // If responsse should array then return empty array
        // when data not available
        $emptyResponse = $this->isArray ? [] : '';
        $output['meta'] = [
            'code' => 200,
            'message' => 'No data available',
            'response_time' => microtime(true) - LARAVEL_START,
            'response_date' => date('Y-m-d H:i:s')
        ];

        $output['data'] = isset($data['data']) ? $data['data'] : $emptyResponse;

        if (is_object($data))
            $data = $data->toArray();

        if (!empty($data)) {
            $output['meta'] = [
                'code' => $success_code,
                'message' => $success_response,
                'response_time' => microtime(true) - LARAVEL_START,
                'response_date' => date('Y-m-d H:i:s')
            ];

            $output['data'] = isset($data['data']) ? $data['data'] : $data;

            if (isset($data['data']) && $this->pagination)
                $output['pagination'] = array_except($data, 'data');

            if (!empty($this->pagination))
                $output['pagination'] = $this->pagination;

            return response()->json($output, $output['meta']['code']);
        }

        return response()->json($output, $output['meta']['code']);
    }

    /**
     * @param  Illuminate\Http\Request $request
     * @param  array $config
     * @param  string $message
     * @return array
     */
    function validateRequest($request, $config, $message = '')
    {
        if (is_null($request)) {
            header('HTTP/1.0 400 Bad Request');
            header('Cache-Control: no-cache');
            header('Content-Type:  application/json');

            exit($this->errorRequest(400, 'Please check all input', [], true));
        }

        $request = is_array($request) ? $request : (array) $request;
        $validate = Validator::make($request, $config);

        if ($validate->fails()) {
            exit($this->errorValidation($validate->errors()->toArray()));
        }
    }

    /**
     * @param  integer $code
     * @param  string $message
     * @param  array $message_aray
     * @param  boolean $echo
     * @return JSON string if echo TRUE else JSON
     */
    function errorRequest($code = '', $message = '', $message_array = [], $echo = false)
    {
        switch ($code) {
            case 400:
                $httpMessage = 'Bad Request';
                break;

            case 401:
                $httpMessage = 'Unauthorized';
                break;

            case 403:
                $httpMessage = 'Forbidden';
                break;

            case 404:
                $httpMessage = 'Not Found';
                break;

            case 405:
                $httpMessage = 'Method Not Allowed';
                break;

            case 422:
                $httpMessage = 'Unprocessable Entity';
                break;

            case 500:
                $httpMessage = 'Internal Server Error';
                break;

            default:
                $httpMessage = 'Internal server error';
                break;
        }

        $output['meta'] = [
            'code' => $code,
            'message' => empty($message) ? $httpMessage : $message,
            'message_array' => $message_array,
            'response_time' => microtime(true) - LARAVEL_START,
            'response_date' => date('Y-m-d H:i:s')
        ];

        if ($echo == true) {
            return json_encode($output);
        }

        return response()->json($output, $output['meta']['code']);
    }

    /**
     * @param  array $errors
     * @return JSON
     */
    function errorValidation(array $errors)
    {
        $errorArray = [];
        $errorFlatten = array_flatten($errors);

        foreach ($errors as $key => $value) {
            $errorArray[$key] = array_first($value);
        }

        $output['meta'] = [
            'code' => 422,
            'message' => array_first($errorFlatten),
            'message_array' => $errorArray,
            'response_time' => microtime(true) - LARAVEL_START,
            'response_date' => date('Y-m-d H:i:s')
        ];

        return response()->json($output, 422);
    }
}