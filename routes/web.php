<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanDosenWaliController;
use App\Http\Controllers\DashboardMahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\InputNilaiController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PrestasiMahasiswaController;
use App\Http\Controllers\RekapPrestasiController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportCenterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::get('/admin/backup-database', [BackupController::class, 'download'])
    ->middleware('auth')
    ->name('admin.backup.database');

Route::get('/dashboard/{nim}', [DashboardMahasiswaController::class, 'show'])
    ->middleware('auth')
    ->name('dashboard.mahasiswa');

Route::post('/dashboard/{nim}/upload-foto', [DashboardMahasiswaController::class, 'uploadFoto'])
    ->middleware('auth')
    ->name('dashboard.mahasiswa.upload-foto');

Route::get('/mahasiswa/ganti-password', [PasswordController::class, 'edit'])
    ->middleware('auth')
    ->name('mahasiswa.password.edit');

Route::get('/dosen/ganti-password', [PasswordController::class, 'editDosen'])
    ->middleware('auth')
    ->name('dosen.password.edit');

Route::put('/dosen/ganti-password', [PasswordController::class, 'updateDosen'])
    ->middleware('auth')
    ->name('dosen.password.update');

Route::put('/mahasiswa/ganti-password', [PasswordController::class, 'update'])
    ->middleware('auth')
    ->name('mahasiswa.password.update');

Route::put('/mahasiswa/ganti-foto', [PasswordController::class, 'updateFoto'])
    ->middleware('auth')
    ->name('mahasiswa.foto.update');

Route::get('/mahasiswa/{nim}/prestasi', [PrestasiMahasiswaController::class, 'index'])
    ->middleware('auth')
    ->name('mahasiswa.prestasi');

Route::post('/mahasiswa/{nim}/prestasi', [PrestasiMahasiswaController::class, 'store'])
    ->middleware('auth')
    ->name('mahasiswa.prestasi.store');

Route::delete('/mahasiswa/{nim}/prestasi/{prestasi}', [PrestasiMahasiswaController::class, 'destroy'])
    ->middleware('auth')
    ->name('mahasiswa.prestasi.destroy');

Route::get('/pengelola/input-nilai', [InputNilaiController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.input-nilai');

Route::post('/pengelola/input-nilai', [InputNilaiController::class, 'store'])
    ->middleware('auth')
    ->name('pengelola.input-nilai.store');

Route::get('/pengelola/input-nilai/{krs}/edit', [InputNilaiController::class, 'edit'])
    ->middleware('auth')
    ->name('pengelola.input-nilai.edit');

Route::put('/pengelola/input-nilai/{krs}', [InputNilaiController::class, 'update'])
    ->middleware('auth')
    ->name('pengelola.input-nilai.update');

Route::get('/pengelola/catatan', [CatatanDosenWaliController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.catatan');

Route::post('/pengelola/catatan', [CatatanDosenWaliController::class, 'store'])
    ->middleware('auth')
    ->name('pengelola.catatan.store');

Route::get('/pengelola/pesan-mahasiswa', [PesanController::class, 'indexAdmin'])
    ->middleware('auth')
    ->name('pengelola.pesan.mahasiswa');

Route::get('/pengelola/mahasiswa', [MahasiswaController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.mahasiswa');

Route::post('/pengelola/mahasiswa', [MahasiswaController::class, 'store'])
    ->middleware('auth')
    ->name('pengelola.mahasiswa.store');

Route::get('/pengelola/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])
    ->middleware('auth')
    ->name('pengelola.mahasiswa.edit');

Route::put('/pengelola/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])
    ->middleware('auth')
    ->name('pengelola.mahasiswa.update');

Route::get('/pengelola/dosen', [DosenController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.dosen');

Route::get('/pengelola/dosen/create', [DosenController::class, 'create'])
    ->middleware('auth')
    ->name('pengelola.dosen.create');

Route::post('/pengelola/dosen', [DosenController::class, 'store'])
    ->middleware('auth')
    ->name('pengelola.dosen.store');

Route::get('/pengelola/dosen/{dosen}/edit', [DosenController::class, 'edit'])
    ->middleware('auth')
    ->name('pengelola.dosen.edit');

Route::put('/pengelola/dosen/{dosen}', [DosenController::class, 'update'])
    ->middleware('auth')
    ->name('pengelola.dosen.update');

Route::delete('/pengelola/dosen/{dosen}', [DosenController::class, 'destroy'])
    ->middleware('auth')
    ->name('pengelola.dosen.destroy');

Route::get('/pengelola/import/mahasiswa/template', [ImportExcelController::class, 'downloadMahasiswaTemplate'])
    ->middleware('auth')
    ->name('pengelola.import.mahasiswa.template');

Route::post('/pengelola/import/mahasiswa', [ImportExcelController::class, 'importMahasiswa'])
    ->middleware('auth')
    ->name('pengelola.import.mahasiswa');

Route::get('/pengelola/import/nilai/template', [ImportExcelController::class, 'downloadNilaiTemplate'])
    ->middleware('auth')
    ->name('pengelola.import.nilai.template');

Route::post('/pengelola/import/nilai', [ImportExcelController::class, 'importNilai'])
    ->middleware('auth')
    ->name('pengelola.import.nilai');

Route::get('/pengelola/mata-kuliah', [MataKuliahController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.mata-kuliah');

Route::post('/pengelola/mata-kuliah', [MataKuliahController::class, 'store'])
    ->middleware('auth')
    ->name('pengelola.mata-kuliah.store');

Route::get('/pengelola/mata-kuliah/{mataKuliah}/edit', [MataKuliahController::class, 'edit'])
    ->middleware('auth')
    ->name('pengelola.mata-kuliah.edit');

Route::put('/pengelola/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])
    ->middleware('auth')
    ->name('pengelola.mata-kuliah.update');

Route::delete('/pengelola/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])
    ->middleware('auth')
    ->name('pengelola.mata-kuliah.destroy');

Route::get('/pengelola/rekap/mahasiswa', [\App\Http\Controllers\RekapController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.rekap.mahasiswa');

Route::get('/pengelola/rekap/mahasiswa/print', [\App\Http\Controllers\RekapController::class, 'print'])
    ->middleware('auth')
    ->name('pengelola.rekap.mahasiswa.print');

Route::get('/pengelola/rekap/prestasi', [RekapPrestasiController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.rekap.prestasi');

// Pesan/Chat routes
Route::middleware('auth')->group(function () {
    // Mahasiswa
    Route::get('/mahasiswa/{nim}/pesan', [PesanController::class, 'indexMahasiswa'])->name('pesan.mahasiswa');
    Route::post('/mahasiswa/{nim}/pesan', [PesanController::class, 'store'])->name('pesan.store');
    
    // Dosen wali
    Route::get('/dosen/pesan', [PesanController::class, 'indexDosen'])->name('pesan.dosen');
    Route::post('/dosen/pesan/{pesan}/balas', [PesanController::class, 'balasDosen'])->name('pesan.dosen.balas');
});

// Report Center Routes
Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportCenterController::class, 'index'])->name('reports.index');
    Route::get('/reports/dosen-perwalian/preview', [ReportCenterController::class, 'dosenPerwalianPreview'])->name('reports.dosen_perwalian.preview');
    Route::post('/reports/dosen-perwalian/pdf', [ReportCenterController::class, 'dosenPerwalianPdf'])->name('reports.dosen_perwalian.pdf');
    Route::get('/reports/student-portfolio/pdf', [ReportCenterController::class, 'studentPortfolioPdf'])->name('reports.student_portfolio.pdf');
    Route::get('/reports/prestasi/export', [ReportCenterController::class, 'prestasiExport'])->name('reports.prestasi.export');
    Route::get('/reports/academic-risk/pdf', [ReportCenterController::class, 'academicRiskPdf'])->name('reports.academic_risk.pdf');
});

use App\Http\Controllers\SmartAdvisorController;

// Smart Advisor Routes
Route::middleware('auth')->group(function () {
    Route::get('/advisor/dosen', [SmartAdvisorController::class, 'indexDosen'])->name('advisor.dosen');
    Route::post('/advisor/dosen/run', [SmartAdvisorController::class, 'runAnalysisDosen'])->name('advisor.dosen.run');
    
    Route::get('/advisor/admin', [SmartAdvisorController::class, 'indexAdmin'])->name('advisor.admin');
    Route::post('/advisor/admin/weights', [SmartAdvisorController::class, 'updateWeights'])->name('advisor.admin.weights');
});

// Dokumentasi Fitur Route
Route::get('/pengelola/dokumentasi', function () {
    return view('pengelola.fitur-dokumentasi');
})
    ->middleware('auth')
    ->name('pengelola.dokumentasi');

// Analisis & Perancangan UML & ERD Route
use App\Http\Controllers\AnalisisPerancanganController;

Route::get('/pengelola/analisis-perancangan', [AnalisisPerancanganController::class, 'index'])
    ->middleware('auth')
    ->name('pengelola.analisis_perancangan');
