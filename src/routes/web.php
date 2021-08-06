<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$router->group(['middleware' => ['auth:web', 'scopes:users']], function () use ($router) {
    Route::get('/test', function () {
        return view('welcome');
    });

    Route::get('/logins', function () {
        return redirect('auth/login');
    });
});


Route::get('reset_password/{token}', ['as' => 'password.reset', function ($token) {
    // implement your reset password route here!
}]);

Route::get('/', function () {
    // echo 'asdas';
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('login');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
