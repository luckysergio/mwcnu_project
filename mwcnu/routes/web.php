<?php

use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataProkerController;
use App\Http\Controllers\JadwalProkerController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/data-proker', [DataProkerController::class, 'index'])->name('data-proker.index');

Route::get('/data-proker', [DataProkerController::class, 'index'])->name('data-proker.index');
Route::get('/data-proker/create', [DataProkerController::class, 'create'])->name('data-proker.create');
Route::post('/data-proker', [DataProkerController::class, 'store'])->name('data-proker.store');

Route::get('/dashboard', [JadwalProkerController::class, 'show'])->middleware('auth');

Route::get('/anggota', [AnggotaController::class, 'index'])->middleware('auth');

Route::middleware(['auth', 'role:Admin,Tanfidiyah'])->group(function () {
    Route::get('/anggota/create', [AnggotaController::class, 'create']);
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id}', [AnggotaController::class, 'edit'])->name('anggota.update');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update']);
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);
});

Route::get('/anggota/{id}/link-user', [AnggotaController::class, 'linkUserForm'])->name('anggota.link-user.form');
Route::post('/anggota/{id}/link-user', [AnggotaController::class, 'linkUser'])->name('anggota.link-user');

Route::get('/account-request', [UserController::class, 'account_request'])->middleware('role:Admin,User');
Route::post('/account-request/approval/{id}', [UserController::class, 'account_approval'])->middleware('role:Admin,User');
Route::get('/count-submitted-users', [UserController::class, 'countSubmittedUsers'])->middleware('role:Admin,User');

Route::get('/proker-request', [ProkerController::class, 'proker_request'])->middleware('role:Admin,User');
Route::get('/proker', [ProkerController::class, 'index'])->middleware('role:Admin,User');
Route::post('/proker-request/approval/{id}', [ProkerController::class, 'proker_approval'])->middleware('role:Admin,User');
Route::get('/proker/create', [ProkerController::class, 'create'])->middleware('role:Admin,User');
Route::post('/proker', [ProkerController::class, 'store'])->middleware('role:Admin,User');
Route::get('/proker/{id}', [ProkerController::class, 'edit'])->middleware('role:Admin,User');
Route::put('/proker/{id}', [ProkerController::class, 'update'])->middleware('role:Admin,User');
Route::delete('/proker/{id}', [ProkerController::class, 'destroy'])->middleware('role:Admin,User');
Route::get('/count-submitted-proker', [ProkerController::class, 'countSubmittedProker'])->middleware('role:Admin,User');

Route::get('/jadwal', [JadwalProkerController::class, 'index'])->middleware('role:Admin,User');
Route::get('/jadwal/create', [JadwalProkerController::class, 'create'])->middleware('role:Admin');
Route::get('/jadwal/{id}', [JadwalProkerController::class, 'edit'])->middleware('role:Admin');
Route::post('/jadwal', [JadwalProkerController::class, 'store'])->middleware('role:Admin');
Route::put('/jadwal/{id}', [JadwalProkerController::class, 'update'])->middleware('role:Admin');
Route::delete('/jadwal/{id}', [JadwalProkerController::class, 'destroy'])->middleware('role:Admin');
Route::get('/count-proker-belum-jadwal', [ProkerController::class, 'countBelumJadwal'])->middleware('role:Admin');

Route::get('/profile', [UserController::class, 'profile_view'])->middleware('role:Admin,User');
Route::put('/profile/{id}', [UserController::class, 'profile_update'])->middleware('role:Admin,User');

Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
Route::get('/anggaran/create', [AnggaranController::class, 'create'])->name('anggaran.create');
Route::post('/anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
Route::get('/anggaran/{id}/edit', [AnggaranController::class, 'edit'])->name('anggaran.edit');
Route::put('/anggaran/{id}', [AnggaranController::class, 'update'])->name('anggaran.update');
Route::delete('/anggaran/{id}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');
Route::get('/anggaran/show-jadwal', [AnggaranController::class, 'show'])->name('anggaran.showJadwal');
Route::get('/anggaran/download-pdf', [AnggaranController::class, 'downloadPdf'])->name('anggaran.downloadPdf');
