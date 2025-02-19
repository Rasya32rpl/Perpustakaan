<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\http\Controllers\SiswaController;
Route::get('/getsiswa',[SiswaController::class,'getsiswa']);
Route::get('/getsiswaid/{id}', [SiswaController::class, 'getsiswaid']);
Route::post('/createsiswa',[SiswaController::class,'createsiswa']);
Route::put('/updatesiswa/{id}', [SiswaController::class,'updatesiswa']);
Route::delete('/deletesiswa/{id}', [SiswaController::class,'deletesiswa']);

use App\http\Controllers\KelasController;
Route::post('/createkelas',[KelasController::class,'createkelas']);

use App\http\Controllers\BukuController;
Route::post('/createbuku',[BukuController::class,'createbuku']);
Route::get('/getbuku',[BukuController::class,'getbuku']);
Route::get('/getbukuid/{id_buku}', [BukuController::class, 'getbukuid']);
Route::put('/updatebuku/{id_buku}', [BukuController::class,'updatebuku']);
Route::delete('/deletebuku/{id_buku}', [BukuController::class,'deletebuku']);

use App\http\Controllers\PeminjamanController;
Route::post('/createpeminjaman',[PeminjamanController::class,'createpeminjaman']);
Route::get('/getpeminjaman',[PeminjamanController::class,'getpeminjaman']);
Route::get('/getpeminjamanid/{id}',[PeminjamanController::class,'getpeminjamanid']);

use App\http\Controllers\PengembalianController;
Route::put('/kembalipeminjaman/{id}',[PengembalianController::class,'kembalipeminjaman']);
Route::get('/getpengembalian',[PengembalianController::class,'getpengembalian']);
Route::get('/getpengembalianid/{id}',[PengembalianController::class,'getpengembalianid']);

use App\http\Controllers\UsersController;
Route::post('/createusers',[UsersController::class,'createusers']);
Route::get('/getusers',[UsersController::class,'getusers']);
Route::get('/getusersid/{id}', [UsersController::class, 'getusersid']);
Route::put('/updateusers/{id}', [UsersController::class,'updateusers']);
Route::delete('/deleteusers/{id}', [UsersController::class,'deleteusers']);

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});