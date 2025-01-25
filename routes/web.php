<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfilPerusahaanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/not-found', function () {
    return view('errors.404');
})->name('not-found');

Route::get('/absen/{nomor_anggota}', [AbsensiController::class, 'showAbsenFormByNomorAnggota'])->name('absen.form.nomor_anggota');
Route::post('/absen/{nomor_anggota}', [AbsensiController::class, 'handleAbsenByNomorAnggota'])->name('absen.handle.nomor_anggota');

Route::post('/check-location', [LocationController::class, 'checkLocation'])->name('check-location');
Route::get('/server-time', [AbsensiController::class, 'getServerTime']);



// Rute untuk dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('absensi/laporan', [AbsensiController::class, 'laporan'])->name('absensi.laporan');
Route::post('absensi/laporan', [AbsensiController::class, 'generateLaporan'])->name('absensi.generateLaporan');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.update');

    Route::resource('karyawan', KaryawanController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::get('profilperusahaan', [ProfilPerusahaanController::class, 'index'])->name('profilperusahaan.index');
    Route::get('profilperusahaan/edit', [ProfilPerusahaanController::class, 'edit'])->name('profilperusahaan.edit');
    Route::post('profilperusahaan/update', [ProfilPerusahaanController::class, 'update'])->name('profilperusahaan.update');

    Route::post('absensi/masuk/{id}', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
    Route::post('absensi/keluar/{id}', [AbsensiController::class, 'keluar'])->name('absensi.keluar');

    // Route::get('absensi/form/{encryptedId}', [KaryawanController::class, 'form'])->name('absensi.form');

});
require __DIR__.'/auth.php';