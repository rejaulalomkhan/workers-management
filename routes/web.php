<?php

use App\Http\Controllers\PdfController;
use App\Livewire\Attendance\AttendanceManager;
use App\Livewire\Auth\Login;
use App\Livewire\Invoices\InvoiceManager;
use App\Livewire\Projects\ProjectManager;
use App\Livewire\Projects\ProjectView;
use App\Livewire\Reports\MonthlyAttendance;
use App\Livewire\Reports\ProfitLoss;
use App\Livewire\Salary\SalaryReport;
use App\Livewire\Settings\SettingsManager;
use App\Livewire\Workers\WorkerManager;
use App\Livewire\Workers\WorkerView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── PWA ───────────────────────────────────────────────────────────────────────
Route::get('/manifest.json', function () {
    $setting = \App\Models\Setting::first();
    $iconUrl = $setting && $setting->logo_path
        ? asset($setting->logo_path)
        : asset('favicon.ico');

    return response()->json([
        'name'             => $setting->company_name ?? 'FHTS System',
        'short_name'       => 'FHTS',
        'description'      => 'Field & HR Tracking System',
        'start_url'        => '/projects',
        'display'          => 'standalone',
        'background_color' => '#0f255a',
        'theme_color'      => '#0f255a',
        'orientation'      => 'portrait-primary',
        'icons'            => [
            ['src' => $iconUrl, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => $iconUrl, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
    ])->header('Content-Type', 'application/manifest+json');
})->name('pwa.manifest');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ── Authenticated Routes ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect('/projects'));

    // Core modules
    Route::get('/projects',           ProjectManager::class);
    Route::get('/projects/{project}', ProjectView::class)->name('projects.view');
    Route::get('/workers',            WorkerManager::class);
    Route::get('/workers/{worker}',   WorkerView::class)->name('workers.view');
    Route::get('/attendance',         AttendanceManager::class);
    Route::get('/salary',             SalaryReport::class);
    Route::get('/invoices',           InvoiceManager::class);
    Route::get('/settings',           SettingsManager::class);

    // Reports
    Route::get('/reports/monthly-attendance', MonthlyAttendance::class)->name('reports.monthly-attendance');
    Route::get('/reports/profit-loss',        ProfitLoss::class)->name('reports.profit-loss');

    // PDF downloads / viewers
    Route::get('/invoices/{id}/view',                        [PdfController::class, 'viewInvoice'])->name('invoices.view');
    Route::get('/salary/worker-pdf/{worker}/{month}/{year}', [PdfController::class, 'workerSalaryPdf'])->name('salary.worker-pdf');
    Route::get('/payment-receipt/{worker}/{month}/{year}',   [PdfController::class, 'paymentReceipt'])->name('payment.receipt');
});
