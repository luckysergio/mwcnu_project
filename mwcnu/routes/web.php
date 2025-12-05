<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataProkerController;
use App\Http\Controllers\JabatanRantingController;
use App\Http\Controllers\JadwalProkerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\ProkerMwcController;
use App\Http\Controllers\ProkerRantingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/anggota', [AnggotaController::class, 'index'])->middleware('auth');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')->middleware('auth');
Route::get('/profile', [UserController::class, 'profile_view'])->name('profile.view')->middleware('auth');
Route::put('/profile/{id}', [UserController::class, 'profile_update'])->name('profile.update')->middleware('auth');


Route::middleware(['auth', 'role:Admin,Tanfidiyah'])->group(function () {
    Route::prefix('jdr')->group(function () {
        Route::get('/', [JabatanRantingController::class, 'index'])->name('jdr.index');
        Route::post('/store', [JabatanRantingController::class, 'store'])->name('jdr.store');
        Route::put('/{type}/{id}', [JabatanRantingController::class, 'update'])->name('jdr.update');
        Route::delete('/{type}/{id}', [JabatanRantingController::class, 'destroy'])->name('jdr.destroy');
    });

    Route::get('/proker-request', [ProkerController::class, 'proker_request'])->name('proker.request');
    Route::post('/proker-approval/{id}', [ProkerController::class, 'proker_approval'])->name('proker.approval');
    Route::get('/count-submitted-proker', [ProkerController::class, 'countSubmittedProker']);

    Route::get('/jadwal', [JadwalProkerController::class, 'index'])->name('jadwal-proker.index');

    Route::get('/count-proker-belum-jadwal', [JadwalProkerController::class, 'countUnassignedProker']);
    Route::get('/jadwal-proker/create', [JadwalProkerController::class, 'create'])->name('jadwal-proker.create');
    Route::post('/jadwal-proker', [JadwalProkerController::class, 'store'])->name('jadwal-proker.store');
    Route::get('/jadwal-proker/{id}/edit', [JadwalProkerController::class, 'edit'])->name('jadwal-proker.edit');
    Route::put('/jadwal-proker/{id}', [JadwalProkerController::class, 'update'])->name('jadwal-proker.update');
    Route::delete('/jadwal-proker/{id}', [JadwalProkerController::class, 'destroy'])->name('jadwal-proker.destroy');
});

Route::middleware(['auth', 'role:Admin,Tanfidiyah,Tanfidiyah ranting,Sekretaris'])->group(function () {
    Route::get('/anggota/create', [AnggotaController::class, 'create']);
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id}', [AnggotaController::class, 'edit'])->name('anggota.update');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update']);
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);

    Route::get('/data-proker', [DataProkerController::class, 'index'])->name('data-proker.index');

    Route::get('/data-proker', [DataProkerController::class, 'index'])->name('data-proker.index');
    Route::get('/data-proker/create', [DataProkerController::class, 'create'])->name('data-proker.create');
    Route::post('/data-proker', [DataProkerController::class, 'store'])->name('data-proker.store');
    Route::put('/data-proker/{type}/{id}', [DataProkerController::class, 'update'])->name('data-proker.update');
    Route::delete('/data-proker/{type}/{id}', [DataProkerController::class, 'destroy'])->name('data-proker.destroy');

    Route::prefix('proker')->name('proker.')->group(function () {
        Route::get('/', [ProkerController::class, 'index'])->name('index');
        Route::get('/create', [ProkerController::class, 'create'])->name('create');
        Route::post('/', [ProkerController::class, 'store'])->name('store');
        Route::get('/{proker}/edit', [ProkerController::class, 'edit'])->name('edit');
        Route::put('/{proker}', [ProkerController::class, 'update'])->name('update');
        Route::delete('/{proker}', [ProkerController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('laporan')->group(function () {
        Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/store', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/{laporan}/update', [LaporanController::class, 'update'])->name('laporan.update');
        Route::get('/export/pdf/{id}', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });

    Route::middleware(['auth'])
        ->prefix('proker-ranting')
        ->name('proker-ranting.')
        ->group(function () {

            Route::get('/', [ProkerRantingController::class, 'index'])->name('index');
            Route::get('/{id}', [ProkerRantingController::class, 'show'])->name('show');
            Route::post('/{id}/update-status', [ProkerRantingController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/upload-foto', [ProkerRantingController::class, 'uploadFoto'])
                ->name('upload-foto');
        });

    Route::middleware(['auth'])
    ->prefix('proker-mwc')
    ->name('proker-mwc.')
    ->group(function () {

        Route::get('/', [ProkerMwcController::class, 'index'])->name('index');
        Route::get('/create', [ProkerMwcController::class, 'create'])->name('create');
        Route::post('/', [ProkerMwcController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [ProkerMwcController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProkerMwcController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProkerMwcController::class, 'destroy'])->name('destroy');

        Route::get('/submitted', [ProkerMwcController::class, 'submitted'])->name('submitted');
        Route::post('/{id}/approve', [ProkerMwcController::class, 'approve'])->name('approve');

        Route::post('/{id}/pilih', [ProkerMwcController::class, 'pilih'])->name('pilih');
        Route::get('/disabled-dates', [ProkerMwcController::class, 'disabledDates'])
      ->name('disabled-dates');
        Route::get('/dashboard/ranting', [DashboardController::class, 'rantingCard']);

    });

});
