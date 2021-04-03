<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function ($api) { // Always keep this to v1, and ignore accept header.
    $api->group(['prefix' => 'v1'], function ($api) { // Use this route group for v1
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
            $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

            $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
            $api->post('verify_token', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@verifyToken');
            $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

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

        #admin endpoint
        $api->group(['prefix' => 'admin'], function (Router $api) {
            $api->post('login', 'App\\Api\\V1\\Controllers\\Admin\\AuthController@login');

            $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
                #admin - comers
                $api->get('comers/', 'App\\Api\\V1\\Controllers\\Admin\\ComersController@index');
                $api->get('comers/{id}', 'App\\Api\\V1\\Controllers\\Admin\\ComersController@show');
                $api->post('comers/activate', 'App\\Api\\V1\\Controllers\\Admin\\ComersController@activate');

                #admin - mate
                $api->get('mate', 'App\\Api\\V1\\Controllers\\Admin\\MateController@index');
                $api->get('mate/{id}', 'App\\Api\\V1\\Controllers\\Admin\\MateController@show');

                #admin - angkatan
                $api->get('angkatan', 'App\\Api\\V1\\Controllers\\Admin\\AngkatanController@index');
                // $api->get('angkatan/{id}', 'App\\Api\\V1\\Controllers\\Admin\\AngkatanController@show');
            });
        });
    });


    $api->group(['prefix' => 'v2'], function ($api) { // Use this route group for v2

        $api->get('/', function () {
            return 'Look v2!';
        });
        $api->get('hello', function () {
            return response()->json([
                'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.22'
            ]);
        });
    });
});
