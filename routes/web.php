<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardAuditorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorController;
use App\Http\Controllers\BuktiPendukungController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VisitasiLapanganController;
use App\Http\Controllers\IndikatorDokumenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DocumentUploadTestController;
use App\Http\Controllers\Auditee\DashboardController as AuditeeDashboardController;

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

/*
|-----------------------------------
| Rute yang Memerlukan Autentikasi
|-----------------------------------
*/
Route::middleware([
    'auth',
    'verified',
])->group(function () {

    // Dashboard default Jetstream
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin: Manajemen Kriteria
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('kriteria', KriteriaController::class);
    });

    // ---------- Halaman Statis/Mockup ----------
    Route::get('dashboard-auditee', [AuditeeDashboardController::class, 'index'])->middleware('role:Auditee')->name('dashboard.auditee');
    Route::middleware('role:Auditor')->get('daftar-audit-auditor', [AuditController::class, 'index'])->name('daftar.audit.auditor');
    Route::view('detail-audit-auditee', 'pages.detail_audit_auditee', [
        'audit' => null,
        'laporan' => null,
    ])->middleware('role:Auditee')->name('detail.audit.auditee');
    Route::middleware('role:Auditor')->get('form-buat-audit-auditor', [AuditController::class, 'create'])->name('form.buat.audit.auditor');
    Route::middleware('role:Auditor')->post('audits', [AuditController::class, 'store'])->name('audits.store');
    Route::middleware('role:Auditor')->get('audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
    Route::middleware('role:Auditor')->get('audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
    Route::middleware('role:Auditor')->patch('audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
    Route::middleware('role:Auditor')->delete('audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');

    // CRUD Indikator
    Route::resource('indikator', IndikatorController::class)->middleware('role:Auditor');
    Route::view('laporan-audit-contoh', 'pages.laporan_audit_contoh')->name('laporan.audit.contoh');
    Route::view('forget-password-page', 'pages.forget_password')->name('forget.password.page');
    Route::view('tambah-dokumen', 'pages.tambah_dokumen')->name('tambah.dokumen');
    Route::view('notifikasi', 'pages.notifikasi')->middleware('role:Auditor|Auditee')->name('notifikasi');
    Route::view('regulasi', 'pages.regulasi')->middleware('role:Auditor|Auditee')->name('regulasi');
    Route::view('forum', 'pages.forum')->middleware('role:Auditor|Auditee')->name('forum');
    Route::view('sertifikasi', 'pages.sertifikasi')->middleware('role:Auditor|Auditee')->name('sertifikasi');

    Route::get('daftar-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'index'])->middleware('role:Auditor')->name('kriteria.index');
    Route::get('insert-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'create'])->middleware('role:Auditor')->name('kriteria.create');
    Route::post('insert-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'store'])->middleware('role:Auditor')->name('kriteria.store');
    Route::get('kriteria/{kriteria}/edit', [App\Http\Controllers\KriteriaController::class, 'edit'])->middleware('role:Auditor')->name('kriteria.edit');
    Route::put('kriteria/{kriteria}', [App\Http\Controllers\KriteriaController::class, 'update'])->middleware('role:Auditor')->name('kriteria.update');
    Route::delete('kriteria/{kriteria}', [App\Http\Controllers\KriteriaController::class, 'destroy'])->middleware('role:Auditor')->name('kriteria.destroy');
    Route::get('insert-sub-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'createSubKriteria'])->middleware('role:Auditor')->name('insert.sub.kriteria.auditor');
    Route::post('insert-sub-kriteria-auditor', [App\Http\Controllers\KriteriaController::class, 'storeSubKriteria'])->middleware('role:Auditor')->name('subkriteria.store');
    Route::resource('bukti-pendukung', BuktiPendukungController::class)->middleware('role:Auditor|Auditee');
    // Tambah approve/reject bukti pendukung untuk Auditor
    Route::patch('bukti-pendukung/{bukti}/approve', [BuktiPendukungController::class, 'approve'])
        ->name('bukti-pendukung.approve')->middleware('role:Auditor');
    Route::patch('bukti-pendukung/{bukti}/reject', [BuktiPendukungController::class, 'reject'])
        ->name('bukti-pendukung.reject')->middleware('role:Auditor');
    // Route::view('profile-page', 'pages.profile')->name('profile.page'); // Route ini menyebabkan error karena tidak mengirimkan data user
    Route::get('history', [AuditController::class, 'history'])->name('history');
    Route::get('history/{audit}/report', [AuditController::class, 'showReport'])->name('history.report');
    Route::get('pelaporan', [LaporanController::class, 'index'])->middleware('role:Auditor')->name('pelaporan');
    Route::post('pelaporan', [LaporanController::class, 'store'])->middleware('role:Auditor')->name('pelaporan.store');
    Route::get('laporan/{audit}/create', [LaporanController::class, 'create'])->middleware('role:Auditor')->name('laporan.create');
    Route::view('tambah-pelaporan', 'pages.tambah_pelaporan')->middleware('role:Auditor')->name('tambah.pelaporan');
    Route::get('visitasi-lapangan', [VisitasiLapanganController::class, 'index'])->middleware('role:Auditor')->name('visitasi.lapangan');
    Route::post('visitasi-lapangan', [VisitasiLapanganController::class, 'store'])->middleware('role:Auditor')->name('visitasi.lapangan.store');
    Route::get('visitasi-lapangan/{visitasi}', [VisitasiLapanganController::class, 'show'])->middleware('role:Auditor')->name('visitasi.lapangan.show');
    Route::patch('visitasi-lapangan/{visitasi}/cancel', [VisitasiLapanganController::class, 'cancel'])->middleware('role:Auditor')->name('visitasi.lapangan.cancel');
    Route::view('tambah-history', 'pages.tambah_history')->name('tambah.history');

    // CRUD Indikator

    Route::resource('indikator-dokumen', IndikatorDokumenController::class)->middleware('role:Auditor');

    // Testing routes for document upload
    Route::prefix('test-upload')->name('test.upload.')->group(function () {
        Route::get('/', [\App\Http\Controllers\DocumentUploadTestController::class, 'index'])->name('index');
        Route::post('/2mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload2MB'])->name('2mb');
        Route::post('/5mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload5MB'])->name('5mb');
        Route::post('/10mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload10MB'])->name('10mb');
        Route::post('/50mb', [\App\Http\Controllers\DocumentUploadTestController::class, 'upload50MB'])->name('50mb');
        Route::get('/results', [\App\Http\Controllers\DocumentUploadTestController::class, 'getTestResults'])->name('results');
        Route::delete('/clear', [\App\Http\Controllers\DocumentUploadTestController::class, 'clearTestData'])->name('clear');
        Route::post('/auto-test', [\App\Http\Controllers\DocumentUploadTestController::class, 'runAutomatedTests'])->name('auto');
        Route::post('/concurrent-test', [\App\Http\Controllers\DocumentUploadTestController::class, 'testConcurrentUploads'])->name('concurrent');
        Route::get('/system-info', [\App\Http\Controllers\DocumentUploadTestController::class, 'getSystemInfo'])->name('system');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute Khusus Auditor (role:Auditor)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role:Auditor'])->prefix('auditor')->name('auditor.')->group(function () {
        Route::resource('kriteria', \App\Http\Controllers\Auditor\KriteriaController::class);
        // CRUD Checklist & Kepatuhan
        Route::resource('checklist-templates', \App\Http\Controllers\Auditor\ChecklistTemplateController::class);
        Route::resource('subkriteria', \App\Http\Controllers\Auditor\SubkriteriaController::class);
        Route::resource('indikator', \App\Http\Controllers\Auditor\IndikatorController::class);
        Route::resource('audits', \App\Http\Controllers\Auditor\AuditController::class);

        // Auditor Review
        Route::post('reviews/{audit}/criterion/{criterion}', [\App\Http\Controllers\Auditor\ReviewController::class, 'store'])->name('reviews.store');

        Route::get('reviews/{audit}', [\App\Http\Controllers\Auditor\ReviewController::class, 'show'])->name('reviews.show');
        Route::post('reviews/{audit}/items/{item}/score', [\App\Http\Controllers\Auditor\ReviewController::class, 'storeScore'])->name('reviews.storeScore');
        Route::post('reviews/{audit}/finalize', [\App\Http\Controllers\Auditor\ReviewController::class, 'finalize'])->name('reviews.finalize');
    });

    /*
    |--------------------------------------------------------------------------
    | Rute Khusus Auditee (role:Auditee)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role:Auditee'])->prefix('auditee')->name('auditee.')->group(function () {
        Route::get('tugas', [\App\Http\Controllers\Auditee\TugasController::class, 'index'])->name('tugas.index');
        Route::get('tugas/{audit}', [\App\Http\Controllers\Auditee\TugasController::class, 'show'])->name('tugas.show');
        Route::get('tugas/{audit}/checklist', [\App\Http\Controllers\Auditee\ChecklistController::class, 'show'])->name('tugas.checklist');
        Route::post('tugas/{audit}/checklist', [\App\Http\Controllers\Auditee\ChecklistController::class, 'store'])->name('tugas.checklist.store');
        Route::get('tugas/{audit}/criterion/{criterion}/edit', [\App\Http\Controllers\Auditee\TugasController::class, 'editTindakLanjut'])->name('tindak_lanjut.edit');
        Route::post('tugas/{audit}/criterion/{criterion}', [\App\Http\Controllers\Auditee\TugasController::class, 'updateTindakLanjut'])->name('tindak_lanjut.update');
        Route::patch('tugas/{audit}/items/{item}', [\App\Http\Controllers\Auditee\ChecklistController::class, 'updateItem'])->name('tugas.items.update');
        Route::post('uploads', [\App\Http\Controllers\Auditee\UploadController::class, 'store'])->name('uploads.store');
    });
});

require __DIR__.'/auth.php';