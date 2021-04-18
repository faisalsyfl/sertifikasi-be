<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="B4T Sertifikasi API",
     *      description="Swagger OpenApi description",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * ),
     *  @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     * @OA\Tag(
     *     name="Auth",
     *     description="Auth Endpoints"
     * )
     * @OA\Tag(
     *     name="Master",
     *     description="Master Endpoints"
     * )
     * @OA\Tag(
     *     name="Form",
     *     description="Form proses pendaftaran sertifikasi"
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}