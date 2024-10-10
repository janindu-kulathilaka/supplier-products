<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;

Route::apiResource('suppliers', SupplierController::class);
Route::get('/getSupplierDetails', [SupplierController::class, 'getSupplierDetails']);
Route::post('/store', [SupplierController::class, 'store']);
// Route::put('/update/{id}', [SupplierController::class, 'update']);
Route::post('/update/{id}', [SupplierController::class, 'update']);
Route::delete('/delete/{id}', [SupplierController::class, 'destroy']);
Route::get('/supplier/{id}', [SupplierController::class, 'show']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
