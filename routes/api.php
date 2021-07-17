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
            $api->get('/dashboard', 'App\\Api\\V1\\Controllers\\TransactionController@dashboard');
        });

        #form qsc endpoint
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'qsc'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->post('store', 'App\\Api\\V1\\Controllers\\TransactionController@store');
            $api->put('status/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@setStatus');

            $api->group(['middleware' => 'jwt.auth', 'prefix' => 'list'], function (Router $api) {
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@list');
            });
        });

        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'qsc2'], function (Router $api) {
            $api->group(['middleware' => 'jwt.auth', 'prefix' => 'list'], function (Router $api) {
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@list');
            });
            $api->group(['middleware' => 'jwt.auth', 'prefix' => 'documents'], function (Router $api) {
                $api->post('/', 'App\\Api\\V1\\Controllers\\Qsc2@documentsUpload');
                $api->get('/', 'App\\Api\\V1\\Controllers\\Qsc2@documentsList');
                $api->post('/edit', 'App\\Api\\V1\\Controllers\\Qsc2@documentUpdate');
                $api->post('/status', 'App\\Api\\V1\\Controllers\\Qsc2@documentUpdateStatus');
                $api->delete('/', 'App\\Api\\V1\\Controllers\\Qsc2@documentsDelete');
            });

            $api->get('/', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->post('store', 'App\\Api\\V1\\Controllers\\TransactionController@store');
        });

        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'qsc3'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->get('/lingkup-suggestion', 'App\\Api\\V1\\Controllers\\Qsc3@getLingkupSuggestion');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@index');
            $api->post('store', 'App\\Api\\V1\\Controllers\\TransactionController@store');

            $api->group(['middleware' => 'jwt.auth', 'prefix' => 'list'], function (Router $api) {
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\TransactionController@list');
            });
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
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AccountController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AccountController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'auditor'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\AuditorController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\AuditorController@create');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AuditorController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'document'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\DocumentController@index');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\DocumentController@index');
            $api->post('/', 'App\\Api\\V1\\Controllers\\DocumentController@create');
            $api->post('/{id}', 'App\\Api\\V1\\Controllers\\DocumentController@update');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\DocumentController@destroy');
        });
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'competence'], function (Router $api) {
            $api->get('/', 'App\\Api\\V1\\Controllers\\CompetenceController@index');
            $api->get('/auditor', 'App\\Api\\V1\\Controllers\\CompetenceController@get_auditor_competence');
            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@index');
            $api->post('/auditor', 'App\\Api\\V1\\Controllers\\CompetenceController@set_auditor_competence');
            $api->post('/', 'App\\Api\\V1\\Controllers\\CompetenceController@create');
            $api->put('/auditor/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@update_auditor_competence');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@update');
            $api->delete('/auditor/{id}', 'App\\Api\\V1\\Controllers\\CompetenceController@destroy_auditor_competence');
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
            $api->get('location/', 'App\\Api\\V1\\Controllers\\FormLocation@index');
            $api->get('location/{id}', 'App\\Api\\V1\\Controllers\\FormLocation@index');
            $api->post('location', 'App\\Api\\V1\\Controllers\\FormLocation@storeFormLocation');
            $api->put('location/{id}', 'App\\Api\\V1\\Controllers\\FormLocation@updateLocation');
            $api->delete('location/{id}', 'App\\Api\\V1\\Controllers\\FormLocation@destroy');
        });

        #profile endpoint
        $api->group(['middleware' => 'jwt.auth', 'prefix' => 'pdf'], function (Router $api) {
            $api->get('invoice', 'App\\Api\\V1\\Controllers\\PdfController@invoice');
            $api->get('penawaran', 'App\\Api\\V1\\Controllers\\PdfController@penawaran');
            $api->get('kwitansi', 'App\\Api\\V1\\Controllers\\PdfController@kwitansi');
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