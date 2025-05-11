<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::get('/anggota', [AnggotaController::class, 'index']);
Route::get('/anggota/create', [AnggotaController::class, 'create']);
Route::get('/anggota/{id}', [AnggotaController::class, 'edit']);
Route::post('/anggota', [AnggotaController::class, 'store']);
Route::put('/anggota/{id}', [AnggotaController::class, 'update']);
Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);

