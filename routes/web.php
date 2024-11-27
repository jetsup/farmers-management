<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
use App\Livewire\FarmersAdmin;
use App\Livewire\IndexAdmin;
use App\Livewire\NotificationsAdmin;
use App\Livewire\RecordsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home')->middleware('guest');

Route::get('/home', function () {
    return view('home');
});

Route::post('/contact-us', [MailController::class, 'contactUs'])->name('contact-us');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Admin Routes
Route::get('/dashboard-admin', IndexAdmin::class)->middleware(['auth:sanctum', /*'verified', 'role:admin'*/])->name('dashboard-admin');
Route::get('/dashboard-admin-data', [IndexAdmin::class, 'getApiData'])->middleware(['auth:sanctum', /*'verified', 'role:admin'*/])->name('dashboard-admin-data');

Route::get('/farmers-admin', FarmersAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('farmers-admin');

Route::get('/records-admin', RecordsAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('records-admin');

Route::get('/notifications-admin', NotificationsAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('notifications-admin');

// Generate PDF
Route::get("generate-pdf", [PDFController::class, "generatePDF"]);

