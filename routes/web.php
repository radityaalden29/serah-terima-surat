<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [SuratController::class, 'dashboard'])->name('dashboard');

Route::get('/surat/template', [SuratController::class, 'downloadTemplate'])->name('surat.template');
Route::post('/surat/import', [SuratController::class, 'import'])->name('surat.import');

Route::resource('surat', SuratController::class);

Route::get('/log-aktivitas', [SuratController::class, 'activityLog'])->name('log-aktivitas');
Route::get('/log-aktivitas/export', [SuratController::class, 'activityLogExport'])->name('log-aktivitas.export');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

Route::get('/laporan/pdf', [LaporanController::class, 'cetak'])->name('laporan.cetak');

Route::get('/laporan/excel', [LaporanController::class, 'excel'])->name('laporan.excel');

Route::view('/tentang', 'tentang.index')->name('tentang');
