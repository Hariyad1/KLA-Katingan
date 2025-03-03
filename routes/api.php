<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AgendaController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ProductsController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('news/public', [NewsController::class, 'index']);
Route::get('news/kategori/{kategori_id}', [NewsController::class, 'getByKategori']);
Route::get('news/flag/{flag}', [NewsController::class, 'getByFlag']);
Route::get('media/slideshow', [MediaController::class, 'getSlideshow']);
Route::get('vote-statistics', [VoteController::class, 'getStatistics']);
Route::post('submit-vote', [VoteController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Protected Resources
    Route::apiResource('agenda', AgendaController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('media', MediaController::class);
    Route::apiResource('news', NewsController::class);
    Route::apiResource('setting', SettingController::class);
    Route::apiResource('statistic', StatisticController::class);
    Route::apiResource('contact', ContactController::class);
    Route::apiResource('vote', VoteController::class);
});
