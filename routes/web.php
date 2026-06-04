<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyReportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingApiController;

// Landing page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $targetDate = config('semon.target_date', '2026-06-15T00:00:00+07:00');
    return view('landing.semon', compact('targetDate'));
})->name('landing');

// Public API endpoints for landing page
Route::get('/api/semon/landing-stats', [LandingApiController::class, 'getLandingStats']);
Route::get('/api/semon/map-data', [LandingApiController::class, 'getMapData']);
Route::get('/api/semon/map-progress', [LandingApiController::class, 'getMapProgress']);
Route::get('/api/semon/kecamatan-breakdown/{idkec}', [LandingApiController::class, 'getKecamatanBreakdown']);

// Dashboard — role-aware (all authenticated users)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Detail Monitoring (Admin and PML only)
Route::get('/monitoring', [DashboardController::class, 'monitoring'])
    ->middleware(['auth'])
    ->name('monitoring');

// PML, PCL, Admin allowed to view history
Route::get('/daily-reports', [DailyReportController::class, 'index'])
    ->middleware(['auth'])
    ->name('daily-reports.index');

// PCL and Admin only CRUD operations
Route::middleware(['auth', 'role:pcl,admin'])->group(function () {
    Route::get('/daily-reports/create', [DailyReportController::class, 'create'])->name('daily-reports.create');
    Route::post('/daily-reports', [DailyReportController::class, 'store'])->name('daily-reports.store');
    // We register show route if needed or omit. Since DailyReportController has no show method, we don't need it.
    Route::get('/daily-reports/{daily_report}/edit', [DailyReportController::class, 'edit'])->name('daily-reports.edit');
    Route::put('/daily-reports/{daily_report}', [DailyReportController::class, 'update'])->name('daily-reports.update');
    Route::patch('/daily-reports/{daily_report}', [DailyReportController::class, 'update']);
    Route::delete('/daily-reports/{daily_report}', [DailyReportController::class, 'destroy'])->name('daily-reports.destroy');
});

// Profile (Breeze default — all roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
