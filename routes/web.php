<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DaftarPeriksaController;
use App\Http\Controllers\DaftarPoliController;
use App\Http\Controllers\DetailPeriksaController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalPeriksaController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\ProfilDokterController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [AdminAuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login/do', [AdminAuthController::class, 'doLogin'])->middleware('guest');

Route::get('registration', [AdminAuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AdminAuthController::class, 'postRegistration'])->name('register.post');
Route::get('/logout', [AdminAuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->get('/', function () {
    $data = [
        'content' => 'admin.dashboard.index'
    ];
    return view('layouts.wrapper', $data);
});

Route::get('/gen-pw', function () {
    $pw = bcrypt('admin');
    dd($pw);
});


// Route::get('/dokter/daftar_pasien', function () {
//     $pw = bcrypt('admin');
//     dd($pw);
// });

Route::prefix('/')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $data = [
            'content' => 'dashboard.index'
        ];
        return view('layouts.wrapper', $data);
    });
    Route::resource('/user', AdminUserController::class);
    Route::resource('/poli', PoliController::class);
    Route::resource('/dokter', DokterController::class);
    Route::resource('/pasien', PasienController::class);
    Route::resource('/obat', ObatController::class);
    Route::resource('/daftar_poli', DaftarPoliController::class);
    Route::post('/daftar_poli/data_jadwal_periksa', [DaftarPoliController::class, 'dataJadwalPeriksa']);
    Route::get('/daftar_poli/{id}', [DaftarPoliController::class, 'show'])->name('daftar_poli.show');
    Route::resource('jadwal_periksa', JadwalPeriksaController::class);

    Route::get('/daftar_pasien', [DokterController::class, 'daftarPasien']);
    Route::get('/periksa/{id}', [DokterController::class, 'periksa'])->name('dokter.periksa');
    Route::post('/periksa/upsert/{id}', [DokterController::class, 'periksaPasien'])->name('dokter.periksa.upsert');
    Route::post('/periksa/{id}', [DokterController::class, 'periksaPasien'])->name('dokter.periksa.save');
    Route::get('/riwayat_pasien', [DokterController::class, 'riwayatPasien'])->name('dokter.riwayat_pasien');
    Route::get('/riwayat_pasien/{id_daftar_poli}', [DokterController::class, 'detailRiwayatPasien'])->name('dokter.detail_riwayat_pasien');
    Route::get('/riwayat_periksa/detail/{id}', [DokterController::class, 'riwayat_periksa_detail'])->name('dokter.riwayat_pasien_detail');
    Route::get('/profile-dokter', function () {
        return view('dashboard.profile-dokter');
    })->name('profile-dokter');
    Route::put('profile/dokter/{id}', ['as' => 'dokter.updateProfile', 'uses' => 'App\Http\Controllers\DokterController@updateProfile']);
});