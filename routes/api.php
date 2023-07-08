<?php

use App\Http\Controllers\ApiJurusanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiMahasiswaController;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/mahasiswa')->group(function(){
    Route::get('/', [ApiMahasiswaController::class, 'get']);
    Route::post('/save', [ApiMahasiswaController::class, 'save']);
    Route::delete('/delete', [ApiMahasiswaController::class, 'delete']);
});

Route::prefix('/jurusan')->group(function(){
    Route::get('/fakultas', [ApiJurusanController::class, 'getFakultas']);
    Route::post('/prodi', [ApiJurusanController::class, 'getProdi']);
});

