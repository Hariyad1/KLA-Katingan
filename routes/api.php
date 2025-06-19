<?php

use App\Models\Klaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AgendaController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DataDukungController;
use App\Http\Controllers\API\KlasterController;
use App\Http\Controllers\API\IndikatorController;
use App\Http\Controllers\API\OpdController;
use App\Http\Controllers\API\ProgramKerjaController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('agenda', [AgendaController::class, 'index']);
Route::get('media', [MediaController::class, 'index']);
Route::get('media/slideshow', [MediaController::class, 'getSlideshow']);
Route::get('news', [NewsController::class, 'index']);
Route::get('setting', [SettingController::class, 'index']);
Route::post('/statistic', [StatisticController::class, 'store']);
Route::post('/statistic/update-activity', [StatisticController::class, 'updateActivity']);
Route::get('contact', [ContactController::class, 'index']);
Route::post('contact', [ContactController::class, 'store']);
Route::get('/program-kerja', [ProgramKerjaController::class, 'index'])->name('api.program.index');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('agenda', AgendaController::class)->except(['index']);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('media', MediaController::class)->except(['index']);
    Route::apiResource('news', NewsController::class)->except(['index']);
    Route::apiResource('statistic', StatisticController::class)->except(['store', 'updateActivity']);
    Route::apiResource('contact', ContactController::class)->except(['index', 'store']);
    Route::apiResource('data-dukung', DataDukungController::class);
    Route::apiResource('klaster', KlasterController::class);
    Route::apiResource('indikator', IndikatorController::class);
    Route::apiResource('opd', OpdController::class);
    Route::delete('data-dukung/file/{id}', [DataDukungController::class, 'destroyFile']);
    Route::post('data-dukung/{id}/update', [DataDukungController::class, 'update']);
    Route::get('/user/news', [NewsController::class, 'getUserNews']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('setting', SettingController::class)->except(['index']);
    Route::post('/news/{id}/approve', [NewsController::class, 'approve']);
    Route::post('/news/{id}/reject', [NewsController::class, 'reject']);
});

Route::get('/klaster/{klaster}/indikators', function (Klaster $klaster) {
    return $klaster->indikators;
});
