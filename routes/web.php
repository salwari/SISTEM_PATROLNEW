<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\JadwalPatroliController;
use App\Http\Controllers\KabidDukbisController;
use App\Http\Controllers\KategoriKebersihanController;
use App\Http\Controllers\KategoriKejadianController;
use App\Http\Controllers\LokasiPatroliController;
use App\Http\Controllers\LokasiKebersihanController;
use App\Http\Controllers\PatroliController;
use App\Http\Controllers\PetugasSecurityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-page');
});

// Authenticated Routes (Sudah Login)
Route::middleware('auth')->group(function () {
    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    //SUPER ADMIN ROUTES
    Route::middleware('role:super_admin')->prefix('super-admin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');

        // Data Cabang
        Route::prefix('data-cabang')->group(function () {
            Route::get('/', [CabangController::class, 'index'])->name('data-cabang.index');
            Route::get('/create', [CabangController::class, 'create'])->name('data-cabang.create');
            Route::post('/', [CabangController::class, 'store'])->name('data-cabang.store');
            Route::get('/{cabang}/edit', [CabangController::class, 'edit'])->name('data-cabang.edit');
            Route::put('/{cabang}', [CabangController::class, 'update'])->name('data-cabang.update');
            Route::delete('/{cabang}', [CabangController::class, 'destroy'])->name('data-cabang.destroy');
        });

        Route::get('/data-petugas', [SuperAdminController::class, 'dataPetugas'])->name('super-admin.data-petugas');
        Route::get('/data-lokasi', [SuperAdminController::class, 'dataLokasi'])->name('super-admin.data-lokasi');
        Route::get('/data-patroli-petugas', [SuperAdminController::class, 'dataPatroliPetugas'])->name('super-admin.data-patroli-petugas');
    });

    // ADMIN ROUTES
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Data Pengguna
        Route::prefix('data-pengguna')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('data-pengguna.index');
            Route::get('/create', [UserController::class, 'create'])->name('data-pengguna.create');
            Route::post('/', [UserController::class, 'store'])->name('data-pengguna.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('data-pengguna.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('data-pengguna.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('data-pengguna.destroy');
        });

        //Data Kategori Kejadian
        Route::prefix('kategori-kejadian')->group(function () {
            Route::get('/', [KategoriKejadianController::class, 'index'])->name('kategori-kejadian.index');
            Route::post('/', [KategoriKejadianController::class, 'store'])->name('kategori-kejadian.store');
            Route::get('/{kategoriKejadian}/edit', [KategoriKejadianController::class, 'edit'])->name('kategori-kejadian.edit');
            Route::put('/{kategoriKejadian}', [KategoriKejadianController::class, 'update'])->name('kategori-kejadian.update');
            Route::delete('/{kategoriKejadian}', [KategoriKejadianController::class, 'destroy'])->name('kategori-kejadian.destroy');
        });

        // Jadwal Patroli
        Route::prefix('jadwal-patroli')->group(function () {
            Route::get('/', [JadwalPatroliController::class, 'index'])->name('jadwal-patroli.index');
            Route::post('/generate-ulang', [JadwalPatroliController::class, 'generateUlang'])->name('jadwal-patroli.generate-ulang');
            Route::post('/', [JadwalPatroliController::class, 'store'])->name('jadwal-patroli.store');
            Route::put('/{jadwalPatroli}', [JadwalPatroliController::class, 'update'])->name('jadwal-patroli.update');
            Route::delete('/{jadwalPatroli}', [JadwalPatroliController::class, 'destroy'])->name('jadwal-patroli.destroy');
            Route::get('/cetak', [JadwalPatroliController::class, 'cetakJadwal'])->name('jadwal-patroli.cetak-jadwal');

        });

        // Lokasi Patroli 
        Route::prefix('lokasi-patroli')->group(function () {
            Route::get('/', [LokasiPatroliController::class, 'index'])->name('lokasi-patroli.index');
            Route::get('/create', [LokasiPatroliController::class, 'create'])->name('lokasi-patroli.create');
            Route::post('/', [LokasiPatroliController::class, 'store'])->name('lokasi-patroli.store');
            Route::get('/{lokasiPatroli}/edit', [LokasiPatroliController::class, 'edit'])->name('lokasi-patroli.edit');
            Route::put('/{lokasiPatroli}', [LokasiPatroliController::class, 'update'])->name('lokasi-patroli.update');
            Route::delete('/{lokasiPatroli}', [LokasiPatroliController::class, 'destroy'])->name('lokasi-patroli.destroy');
            Route::get('/{lokasiPatroli}/download-qr', [LokasiPatroliController::class, 'downloadQrCode'])->name('lokasi-patroli.downloadQrCode');
        });

        // Lokasi Kebersihan (Untuk OB)
        Route::prefix('lokasi-kebersihan')->group(function () {
            Route::get('/', [LokasiKebersihanController::class, 'index'])->name('lokasi-kebersihan.index');
            Route::get('/create', [LokasiKebersihanController::class, 'create'])->name('lokasi-kebersihan.create');
            Route::post('/', [LokasiKebersihanController::class, 'store'])->name('lokasi-kebersihan.store');
            Route::get('/{lokasiKebersihan}/edit', [LokasiKebersihanController::class, 'edit'])->name('lokasi-kebersihan.edit');
            Route::put('/{lokasiKebersihan}', [LokasiKebersihanController::class, 'update'])->name('lokasi-kebersihan.update');
            Route::delete('/{lokasiKebersihan}', [LokasiKebersihanController::class, 'destroy'])->name('lokasi-kebersihan.destroy');
            Route::get('/{lokasiKebersihan}/download-qr', [LokasiKebersihanController::class, 'downloadQrCode'])->name('lokasi-kebersihan.downloadQrCode');
        });

        // Kategori Kebersihan
        Route::prefix('kategori-kebersihan')->group(function () {
            Route::get('/', [KategoriKebersihanController::class, 'index'])->name('kategori-kebersihan.index');
            Route::post('/', [KategoriKebersihanController::class, 'store'])->name('kategori-kebersihan.store');
            Route::put('/{checklistKamarMandi}', [KategoriKebersihanController::class, 'update'])->name('kategori-kebersihan.update');
            Route::delete('/{checklistKamarMandi}', [KategoriKebersihanController::class, 'destroy'])->name('kategori-kebersihan.destroy');
        });

        //Validasi Kejadian Darurat
        // Route::get('/validasi-kejadian-darurat', [PatroliController::class, 'validasiKejadianDarurat'])->name('admin.validasi-kejadian-darurat');
        // Route::put('/validasi-kejadian-darurat/{id}', [PatroliController::class, 'updateValidasiDarurat'])->name('admin.update-validasi-darurat');
        
        //Data Patroli
        Route::get('/data-patroli', [AdminController::class, 'dataPatroli'])->name('admin.data-patroli');
    });

    // PETUGAS SECURITY ROUTES
    Route::middleware('role:petugas_security')->prefix('petugas-security')->group(function () {
        Route::get('/dashboard', [PetugasSecurityController::class, 'dashboard'])->name('petugas-security.dashboard');
        Route::get('/scan-qr', [PetugasSecurityController::class, 'scanQR'])->name('petugas-security.scan-qr');
        Route::get('/riwayat-patroli', [PetugasSecurityController::class, 'riwayatPatroli'])->name('petugas-security.riwayat-patroli');

        Route::get('/patroli/create', [PatroliController::class, 'create'])->name('patroli.create');
        Route::post('/patroli/store', [PatroliController::class, 'store'])->name('patroli.store');
    });

    // KABID DUKBIS ROUTES
    Route::middleware('role:kabid_dukbis')->prefix('kabid-dukbis')->group(function () {
        Route::get('/dashboard', [KabidDukbisController::class, 'dashboard'])->name('kabid-dukbis.dashboard');
        Route::get('/data-petugas-security', [KabidDukbisController::class, 'dataPetugasSecurity'])->name('kabid-dukbis.data-petugas-security');
        Route::get('/data-lokasi-patroli', [KabidDukbisController::class, 'dataLokasiPatroli'])->name('kabid-dukbis.data-lokasi-patroli');
        Route::get('/laporan-patroli', [KabidDukbisController::class, 'laporanPatroli'])->name('kabid-dukbis.laporan-patroli');
        Route::get('/laporan-patroli/cetak/redirect', [KabidDukbisController::class, 'redirectCetakLaporan'])->name('kabid-dukbis.cetak-laporan-patroli-redirect');
        // Route::get('/laporan-patroli/cetak/{token}', [KabidDukbisController::class, 'cetakLaporanTerenkripsi'])->name('kabid-dukbis.cetak-laporan-patroli');
        // Route::get('/laporan-patroli/verifikasi', [KabidDukbisController::class, 'verifikasiLaporan'])->name('kabid-dukbis.verifikasi-laporan-patroli');
    });
});

Route::get('/kabid-dukbis/laporan-patroli/cetak/{token}', [KabidDukbisController::class, 'cetakLaporanTerenkripsi'])->name('kabid-dukbis.cetak-laporan-patroli');

//Verifikasi laporan
// Route::get('/verifikasi-laporan/{token}', [KabidDukbisController::class, 'verifikasiLaporanToken'])
//     ->name('verifikasi-laporan-patroli-token');


require __DIR__ . '/auth.php';  // Menambahkan route auth
