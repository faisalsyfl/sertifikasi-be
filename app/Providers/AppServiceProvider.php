<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\Traits\RestApi;

class AppServiceProvider extends ServiceProvider
{
    use RestApi;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('Dingo\Api\Exception\Handler')->register(function (UnauthorizedHttpException $exception) {
            return $this->errorRequest(401, $exception->getMessage());
        });

        app('Dingo\Api\Exception\Handler')->register(function (TokenBlacklistedException $exception) {
            return $this->errorRequest(401, $exception->getMessage());
        });

        app('Dingo\Api\Exception\Handler')->register(function (TokenExpiredException $exception) {
            return $this->errorRequest(401, $exception->getMessage());
        });

        app('Dingo\Api\Exception\Handler')->register(function (TokenInvalidException $exception) {
            return $this->errorRequest(401, $exception->getMessage());
        });

        app('Dingo\Api\Exception\Handler')->register(function (MethodNotAllowedHttpException $exception) {
            return $this->errorRequest(405, $exception->getMessage());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}