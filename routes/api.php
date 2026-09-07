<?php

use App\Http\Controllers\KategoriItemAPIController;
use App\Http\Controllers\MasterItemAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Master Items API
Route::get('/master-items', [MasterItemAPIController::class, 'index']);
Route::post('/master-items', [MasterItemAPIController::class, 'store']);
Route::put('/master-items/{id}', [MasterItemAPIController::class, 'update']);

// Kategori Items API
Route::get('/kategori-items', [KategoriItemAPIController::class, 'index']);
Route::post('/kategori-items', [KategoriItemAPIController::class, 'store']);
Route::put('/kategori-items/{id}', [KategoriItemAPIController::class, 'update']);
