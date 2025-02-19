<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AgendaController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes
Route::apiResource('products', ProductsController::class);

// auth:user routes
Route::post('register', [authController::class, 'register']);
Route::post('login', [authController::class, 'login']);
Route::post('submit-vote', [VoteController::class, 'store']);

// Public Routes
Route::get('news/public', [NewsController::class, 'index']);
Route::get('news/kategori/{kategori_id}', [NewsController::class, 'getByKategori']);
Route::get('news/flag/{flag}', [NewsController::class, 'getByFlag']);
Route::get('media/slideshow', [MediaController::class, 'getSlideshow']);
Route::get('vote-statistics', [VoteController::class, 'getStatistics']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [authController::class, 'logout']);
    
    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::put('users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
    });
    
    // Regular Protected Routes
    Route::apiResources([
        'agenda' => AgendaController::class,
        'contact' => ContactController::class,
        'kategori' => KategoriController::class,
        'media' => MediaController::class,
        'news' => NewsController::class,
        'setting' => SettingController::class,
        'statistic' => StatisticController::class,
        'votes' => VoteController::class,
    ]);
});
