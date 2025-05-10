<?php

use App\Http\Controllers\AnggotaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::get('/anggota', [AnggotaController::class, 'index']);
Route::get('/anggota/create', [AnggotaController::class, 'create']);
Route::get('/anggota/{id}', [AnggotaController::class, 'edit']);
Route::post('/anggota', [AnggotaController::class, 'store']);
Route::put('/anggota/{id}', [AnggotaController::class, 'update']);
Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);

