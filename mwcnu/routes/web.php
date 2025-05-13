<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('role:Admin,User');

Route::get('/anggota', [AnggotaController::class, 'index'])->middleware('role:Admin,User');
Route::get('/anggota/create', [AnggotaController::class, 'create'])->middleware('role:Admin');
Route::get('/anggota/{id}', [AnggotaController::class, 'edit'])->middleware('role:Admin');
Route::post('/anggota', [AnggotaController::class, 'store'])->middleware('role:Admin');
Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->middleware('role:Admin');
Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->middleware('role:Admin');

Route::get('/account-request', [UserController::class, 'account_request']);
Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval']);
Route::get('/count-submitted-users', [UserController::class, 'countSubmittedUsers'])->middleware('role:Admin');

Route::get('/proker-request', [ProkerController::class, 'proker_request']);
Route::get('/proker', [ProkerController::class, 'index']);
Route::post('/proker-request/approval/{id}', [ProkerController::class, 'proker_approval']);
Route::get('/proker/create', [ProkerController::class, 'create'])->middleware('role:Admin,User');
Route::post('/proker', [ProkerController::class, 'store'])->middleware('role:Admin,User');
Route::get('/count-submitted-proker', [ProkerController::class, 'countSubmittedProker'])->middleware('role:Admin');
