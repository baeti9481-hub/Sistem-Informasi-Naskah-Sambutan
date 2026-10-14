<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SambutanController;
use App\Http\Controllers\PengajuanKeprotokolanController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JenisSuratController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// --- ROUTE FRONTEND / PUBLIK (TANPA LOGIN) ---
// ==========================================

Route::get('/', function () {
    return view('frontend.v_beranda.index');
})->name('beranda');

Route::get('/frontend/beranda', function () {
    return view('frontend.v_beranda.index');
});

// Route Frontend Berdasarkan Jenis Surat (JenisSuratController)
Route::get('/surat/sambutan', [JenisSuratController::class, 'sambutan'])->name('frontend.sambutan');
Route::get('/surat-sambutan', [JenisSuratController::class, 'sambutan'])->name('frontend.v_sambutan.index'); // Alias untuk mendukung nama route lama

Route::get('/surat/keprotokolan', [JenisSuratController::class, 'keprotokolan'])->name('frontend.keprotokolan');
Route::get('/surat/sambutan-keprotokolan', [JenisSuratController::class, 'sambutanKeprotokolan'])->name('frontend.sambutan_keprotokolan');

// Pengajuan Keprotokolan Frontend
Route::get('/pengajuan-keprotokolan', [PengajuanKeprotokolanController::class, 'indexFrontend'])->name('frontend.v_keprotokolan.index');
Route::get('/pengajuan-keprotokolan/tambah', [PengajuanKeprotokolanController::class, 'create'])->name('frontend.v_keprotokolan.create');
Route::post('/pengajuan-keprotokolan/simpan', [PengajuanKeprotokolanController::class, 'store'])->name('frontend.v_keprotokolan.store');
Route::get('/reload-captcha', [PengajuanKeprotokolanController::class, 'reloadCaptcha'])->name('reload.captcha');


// ==========================================
// --- ROUTE LOGIN ---
// ==========================================

Route::get('/backend/login', [LoginController::class, 'index'])->name('login');
Route::post('/backend/login', [LoginController::class, 'authenticate'])->name('login.proses');
// Route Halaman Lupa Password
Route::get('/backend/lupa-password', [LoginController::class, 'lupaPassword'])->name('lupa_password');
Route::post('/backend/lupa-password', [LoginController::class, 'prosesLupaPassword'])->name('lupa_password.proses');

// ==========================================
// --- ROUTE BACKEND (HARUS LOGIN) ---
// ==========================================

Route::middleware('auth')->group(function () {
    
    // Logout
    Route::match(['get', 'post'], '/backend/logout', [LoginController::class, 'logout'])->name('backend.logout');

    // Beranda / Dashboard Utama
    Route::get('/backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.v_beranda.index');
    Route::get('/beranda', fn() => redirect()->route('backend.v_beranda.index'));

    // Route User (Resource)
    Route::resource('backend/user', UserController::class, ['as' => 'backend']);

    // Route Group Backend Sidebar
    Route::prefix('backend')->name('backend.')->group(function () {

        // 1. Sub-menu Sambutan
        Route::resource('sambutan', SambutanController::class);

        // 2. Sub-menu Keprotokolan (Backend CRUD)
        Route::get('/keprotokolan', [PengajuanKeprotokolanController::class, 'index'])->name('keprotokolan.index');
        Route::get('/keprotokolan/create', [PengajuanKeprotokolanController::class, 'createBackend'])->name('keprotokolan.create');
        Route::post('/keprotokolan', [PengajuanKeprotokolanController::class, 'storeBackend'])->name('keprotokolan.store');
        Route::get('/keprotokolan/{id}', [PengajuanKeprotokolanController::class, 'show'])->name('keprotokolan.show');
        Route::get('/keprotokolan/{id}/edit', [PengajuanKeprotokolanController::class, 'edit'])->name('keprotokolan.edit');
        Route::put('/keprotokolan/{id}', [PengajuanKeprotokolanController::class, 'update'])->name('keprotokolan.update');
        Route::delete('/keprotokolan/{id}', [PengajuanKeprotokolanController::class, 'destroy'])->name('keprotokolan.destroy');
        Route::put('/keprotokolan/{id}/verifikasi', [PengajuanKeprotokolanController::class, 'verifikasi'])->name('keprotokolan.verifikasi');


        // Route Frontend Keprotokolan
 Route::get('/surat/keprotokolan', [SambutanController::class, 'frontendKeprotokolan'])->name('frontend.keprotokolan.index');
    });

});