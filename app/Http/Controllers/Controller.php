<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="KLA Katingan API Documentation",
 *     description="API Documentation for KLA Katingan"
 * )
 * 
 * @OA\Server(
 *     description="Local Environment",
 *     url="http://localhost:8000"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

abstract class Controller
{
    //
}
