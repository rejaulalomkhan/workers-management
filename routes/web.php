<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Settings\SettingsManager;
use App\Livewire\Projects\ProjectManager;
use App\Livewire\Projects\ProjectView;
use App\Livewire\Workers\WorkerManager;
use App\Livewire\Workers\WorkerView;
use App\Livewire\Attendance\AttendanceManager;
use App\Livewire\Salary\SalaryReport;
use App\Livewire\Invoices\InvoiceManager;
use App\Livewire\Reports\ProfitLoss;
use App\Livewire\Auth\Login;

Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/projects');
    });

    Route::get('/settings', SettingsManager::class);
    Route::get('/reports/monthly-attendance', \App\Livewire\Reports\MonthlyAttendance::class)->name('reports.monthly-attendance');
    Route::get('/reports/profit-loss', ProfitLoss::class)->name('reports.profit-loss');
    Route::get('/projects', ProjectManager::class);
    Route::get('/projects/{project}', ProjectView::class)->name('projects.view');
    Route::get('/workers', WorkerManager::class);
    Route::get('/workers/{worker}', WorkerView::class)->name('workers.view');
    Route::get('/attendance', AttendanceManager::class);
    Route::get('/salary', SalaryReport::class);
    Route::get('/invoices', InvoiceManager::class);
    Route::get('/invoices/{id}/view', function ($id) {
        $invoice = \App\Models\Invoice::with('items', 'project')->findOrFail($id);
        $setting = \App\Models\Setting::first();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.invoice', [
            'invoice' => $invoice,
            'setting' => $setting,
        ])->setPaper('a4', 'portrait');
        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    })->name('invoices.view');
});
