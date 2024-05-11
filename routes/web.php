<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterWilayahController;
use App\Http\Controllers\Admin\MasterDinasController;
use App\Http\Controllers\Admin\MasterTatananController;
use App\Http\Controllers\Admin\MasterIndikatorController;
use App\Http\Controllers\Admin\MasterKelembagaanController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\PengisianFormController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rute-rute otentikasi menggunakan Auth::routes()
Auth::routes();

// Rute-rute lain menggunakan LoginRegisterController
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginRegisterController::class, 'logout'])->name('logout');

    // group master
    Route::prefix('master')->group(function () {
        // master wilayah
        Route::get('/wilayah', [MasterWilayahController::class, 'index'])->name('master.wilayah');
        Route::post('/wilayah/store', [MasterWilayahController::class, 'store'])->name('master.wilayah.store');
        Route::get('/wilayah/{id}/edit', [MasterWilayahController::class, 'edit'])->name('master.wilayah.edit');
        Route::put('/wilayah/{id}/update', [MasterWilayahController::class, 'update'])->name('master.wilayah.update');
        Route::delete('/wilayah/{id}/delete', [MasterWilayahController::class, 'delete'])->name('master.wilayah.delete');
        // master dinas
        Route::get('/dinas', [MasterDinasController::class, 'index'])->name('master.dinas');
        Route::post('/dinas/store', [MasterDinasController::class, 'store'])->name('master.dinas.store');
        Route::get('/dinas/{id}/edit', [MasterDinasController::class, 'edit'])->name('master.dinas.edit');
        Route::post('/dinas/{id}/update', [MasterDinasController::class, 'update'])->name('master.dinas.update');
        Route::get('/dinas/{id}/delete', [MasterDinasController::class, 'destroy'])->name('master.dinas.delete');
        // master indikator
         Route::get('/indikator', [MasterIndikatorController::class, 'index'])->name('master.indikator');
         Route::post('/indikator/store', [MasterIndikatorController::class, 'store'])->name('master.indikator.store');
         Route::get('/indikator/{id}/edit', [MasterIndikatorController::class, 'edit'])->name('master.indikator.edit');
         Route::post('/indikator/{id}/update', [MasterIndikatorController::class, 'update'])->name('master.indikator.update');
         Route::get('/indikator/{id}/delete', [MasterIndikatorController::class, 'destroy'])->name('master.indikator.delete');
        // master kegiatan
        Route::get('/tatanan', [MasterTatananController::class, 'index'])->name('master.tatanan');
        Route::post('/tatanan/store', [MasterTatananController::class, 'store'])->name('master.tatanan.store');
        Route::get('/tatanan/{id}/edit', [MasterTatananController::class, 'edit'])->name('master.tatanan.edit');
        Route::post('/tatanan/{id}/update', [MasterTatananController::class, 'update'])->name('master.tatanan.update');
        Route::get('/tatanan/{id}/delete', [MasterTatananController::class, 'destroy'])->name('master.tatanan.delete');
        // master kelembagaan
        Route::get('/kelembagaan', [MasterKelembagaanController::class, 'index'])->name('master.kelembagaan');
        Route::post('/kelembagaan/store', [MasterKelembagaanController::class, 'store'])->name('master.kelembagaan.store');
        Route::get('/kelembagaan/{id}/edit', [MasterKelembagaanController::class, 'edit'])->name('master.kelembagaan.edit');
        Route::post('/kelembagaan/{id}/update', [MasterKelembagaanController::class, 'update'])->name('master.kelembagaan.update');
        Route::get('/kelembagaan/{id}/delete', [MasterKelembagaanController::class, 'destroy'])->name('master.kelembagaan.delete');
        // master pertanyaan kelembagaan
        Route::get('/pertanyaan-lembaga', [MasterKelembagaanController::class, 'pertanyaan'])->name('master.pertanyaan-lembaga');
        Route::post('/pertanyaan-lembaga/store', [MasterKelembagaanController::class, 'pertanyaanStore'])->name('master.pertanyaan-lembaga.store');
        Route::get('/pertanyaan-lembaga/{id}/edit', [MasterKelembagaanController::class, 'pertanyaanEdit'])->name('master.pertanyaan-lembaga.edit');
        Route::post('/pertanyaan-lembaga/{id}/update', [MasterKelembagaanController::class, 'pertanyaanUpdate'])->name('master.pertanyaan-lembaga.update');
        Route::get('/pertanyaan-lembaga/{id}/delete', [MasterKelembagaanController::class, 'pertanyaanDestroy'])->name('master.pertanyaan-lembaga.delete');
        // master pertanyaan tatanan
        Route::get('/pertanyaan-tatanan', [MasterTatananController::class, 'pertanyaan'])->name('master.pertanyaan-tatanan');
        Route::post('/pertanyaan-tatanan/store', [MasterTatananController::class, 'pertanyaanStore'])->name('master.pertanyaan-tatanan.store');
        Route::get('/pertanyaan-tatanan/{id}/edit', [MasterTatananController::class, 'pertanyaanEdit'])->name('master.pertanyaan-tatanan.edit');
        Route::post('/pertanyaan-tatanan/{id}/update', [MasterTatananController::class, 'pertanyaanUpdate'])->name('master.pertanyaan-tatanan.update');
        Route::get('/pertanyaan-tatanan/{id}/delete', [MasterTatananController::class, 'pertanyaanDestroy'])->name('master.pertanyaan-tatanan.delete');

    });
    Route::prefix('periode')->group(function () {
        Route::get('/tatanan', [PeriodeController::class, 'periode'])->name('periode.tatanan');
        Route::post('/tatanan/store', [PeriodeController::class, 'periode_store'])->name('periode.tatanan.store');
        Route::post('/periode/update-status', [PeriodeController::class, 'updateStatus'])->name('periode.updateStatus');
        Route::post('/periode/update-status/lembaga', [PeriodeController::class, 'updateStatuslembaga'])->name('periode.updateStatuslembaga');
        Route::get('/tatanan/{id}/delete', [PeriodeController::class, 'periode_destroy'])->name('periode.tatanan.delete');
    });

    Route::prefix('pengisianform')->group(function () {
        Route::get('/tatanan', [PengisianFormController::class, 'periode_tatanan'])->name('pengisianform.tatanan');
        Route::get('/assessment/{id}', [PengisianFormController::class, 'assessment']);
        Route::get('/kelembagaan/{id}', [PengisianFormController::class, 'kelembagaan']);
        Route::get('/pertanyaanlist/{id}', [PengisianFormController::class, 'pertanyaanlist']);
        Route::get('/pertanyaanlembaga/{id}', [PengisianFormController::class, 'pertanyaanlembaga']);
        Route::get('/lembaga', [PengisianFormController::class, 'periode_lembaga'])->name('pengisianform.lembaga');
        Route::post('/updatelink', [PengisianFormController::class, 'updatelink'])->name('pengisianform.updatelink');
        Route::post('/updatefilelembaga', [PengisianFormController::class,'updatefilelembaga'])->name('pengisianform.updatefilelembaga');
        Route::post('/pengiisiansoal', [PengisianFormController::class,'pengisianSoal'])->name('pengisianform.pengisianSoal');
        Route::post('/pengiisiansoallembaga', [PengisianFormController::class,'pengisianSoallembaga'])->name('pengisianform.pengisianSoallembaga');
        Route::post('/submitpengisian', [PengisianFormController::class,'submitpengisian'])->name('pengisianform.submitpengisian');
        Route::post('/uploadfile', [PengisianFormController::class, 'uploadfile'])->name('pengisianform.uploadfile');
        Route::post('/start', [PengisianFormController::class, 'start'])->name('pengisianform.start');
        Route::get('/downloadfile/{id}', [PengisianFormController::class, 'downloadfile']);



    });


});

Route::middleware('auth')->get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/login', [LoginRegisterController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginRegisterController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [LoginRegisterController::class, 'register'])->name('register');
Route::post('/store', [LoginRegisterController::class, 'store'])->name('store');
