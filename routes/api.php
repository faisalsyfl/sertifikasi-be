<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function (Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');

        $api->post('checkuser', 'App\\Api\\V1\\Controllers\\SignUpController@checkUser');
        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
        $api->get('admin', 'App\\Api\\V1\\Controllers\\UserController@index');
    });

    #profile endpoint
    $api->group(['middleware' => 'jwt.auth', 'prefix' => 'profile'], function (Router $api) {
        $api->post('me', 'App\\Api\\V1\\Controllers\\ProfileController@me');
        $api->post('edit', 'App\\Api\\V1\\Controllers\\ProfileController@edit');
    });
    #program endpoint
    $api->group(['middleware' => 'jwt.auth', 'prefix' => 'program'], function (Router $api) {
        $api->get('list', 'App\\Api\\V1\\Controllers\\ProgramController@list');
    });

    #task endpoint
    $api->group(['middleware' => 'jwt.auth', 'prefix' => 'task'], function (Router $api) {
        $api->post('list', 'App\\Api\\V1\\Controllers\\TaskController@list');
        $api->post('detail', 'App\\Api\\V1\\Controllers\\TaskController@detail');
        $api->post('update_status', 'App\\Api\\V1\\Controllers\\TaskController@updateStatusTask');
    });

    #protect with Jwt Auth
    $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->get('protected', function () {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function () {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function () {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});