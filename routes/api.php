<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;


Route::post('login', [UserController::class, 'login']);
Route::post('user/tambah', [UserController::class, 'register']);

Route::group(['middleware' => ['jwt.verify:admin,kasir,owner']], function() {
    Route::post('login/check', [UserController::class, 'loginCheck']);
    Route::post('logout', [UserController::class, 'logout']);
    
});

//Route khusus admin
Route::group(['middleware' => ['jwt.verify:admin']], function() {
    
    //OUTLET
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    
    //PAKET
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::post('paket', [PaketController::class, 'store']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);
    
});


//Route khusus admin & kasir
Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {
    //MEMBER
    Route::post('member', [MemberController::class, 'store']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id}', [MemberController::class, 'getById']);
    Route::put('member/{id}', [MemberController::class, 'update']);
    Route::delete('member/{id}', [MemberController::class, 'delete']);

    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::post('transaksi/status', [TransaksiController::class, 'changeStatus']);
    Route::post('transaksi/bayar', [TransaksiController::class, 'bayar']);
});


