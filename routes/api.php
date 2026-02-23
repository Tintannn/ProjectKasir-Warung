<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\DetailTransaksiController;

Route::post('/users/register',[AuthController::class,'register']);
Route::post('/users/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){

Route::post('/users/logout',[AuthController::class,'logout']);

Route::apiResource('barang',BarangController::class);
Route::apiResource('transaksi',TransaksiController::class);
Route::apiResource('detail-transaksi', DetailTransaksiController::class);
});