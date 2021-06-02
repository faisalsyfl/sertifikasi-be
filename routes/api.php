<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function ($api) { // Always keep this to v1, and ignore accept header.
    $api->group(['prefix' => 'v1'], function ($api) { // Use this route group for v1
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
            $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

            $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
            $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');

            $api->post('checkuser', 'App\\Api\\V1\\Controllers\\SignUpController@checkUser');
            $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
            $api->get('admin', 'App\\Api\\V1\\Controllers\\UserController@index');
        });

        #profile endpoint
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'profile'], function (Router $api) {
            $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
        });

        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'qsc'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->post('step1', 'App\\Api\\V1\\Controllers\\TransactionController@qsc1');
            $api->post('step2', 'App\\Api\\V1\\Controllers\\TransactionController@qsc2');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'organization'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\OrganizationController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\OrganizationController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\OrganizationController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\OrganizationController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\OrganizationController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'account'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\AccountController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\AccountController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\AccountController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AccountController@store');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AccountController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'auditor'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\AuditorController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\AuditorController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'competence'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\CompetenceController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@index');
            $api->post('/auditor', 'App\\Api\\V1\\Controllers\\CompetenceController@set_auditor_competence');
            $api->post('/', 'App\\Api\\V1\\Controllers\\CompetenceController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'auditi'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\AuditiController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\AuditiController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\AuditiController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AuditiController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AuditiController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'contact'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\ContactController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\ContactController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\ContactController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\ContactController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\ContactController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'country'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\MasterLocation@showCountry');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'state'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\MasterLocation@showState');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'city'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\MasterLocation@showCity');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'form'], function (Router $api) {
            $api->post('location', 'App\\Api\\V1\\Controllers\\Form\\FormLocation@storeFormLocation');
            $api->delete('location/{id}', 'App\\Api\\V1\\Controllers\\Form\\FormLocation@destroy');
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
                'message' => 'This is a simple example of item returned by your APIs. Everyone can see it. v1'
            ]);
        });
    });


    $api->group(['prefix' => 'v2'], function ($api) { // Use this route group for v2

        $api->get('/', function () {
            return 'Look v2!';
        });
        $api->get('hello', function () {
            return response()->json([
                'message' => 'This is a simple example of item returned by your V2 APIs.'
            ]);
        });
    });
});