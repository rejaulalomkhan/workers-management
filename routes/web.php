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
    Route::get('/projects', ProjectManager::class);
    Route::get('/projects/{project}', ProjectView::class)->name('projects.view');
    Route::get('/workers', WorkerManager::class);
    Route::get('/workers/{worker}', WorkerView::class)->name('workers.view');
    Route::get('/attendance', AttendanceManager::class);
    Route::get('/salary', SalaryReport::class);
    Route::get('/invoices', InvoiceManager::class);
});
