<?php

use App\Http\Controllers\FarmerController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
use App\Livewire\Dashboard;
use App\Livewire\FarmersAdmin;
use App\Livewire\IndexAdmin;
use App\Livewire\NotificationsAdmin;
use App\Livewire\RecordsAdmin;
use App\Livewire\ReportsFarmer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home')->middleware('guest');

Route::get('/home', function () {
    return view('index');
});

Route::post('/contact-us', [MailController::class, 'contactUs'])->name('contact-us');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // return view('dashboard');
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('dashboard-admin');
        } else {
            return redirect()->route('dashboard-farmer');
        }
    })->name('dashboard');
});

// Admin Routes
Route::get('/dashboard-admin', IndexAdmin::class)->middleware(['auth:sanctum', /*'verified', 'role:admin'*/])->name('dashboard-admin');
Route::get('/dashboard-admin-data', [IndexAdmin::class, 'getApiData'])->middleware(['auth:sanctum', /*'verified', 'role:admin'*/])->name('dashboard-admin-data');

Route::get('/farmers-admin', FarmersAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('farmers-admin');

Route::get('/records-admin', RecordsAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('records-admin');

Route::get('/notifications-admin', NotificationsAdmin::class)->middleware(['auth:sanctum'/*, 'verified', 'role:admin'*/])->name('notifications-admin');

// Farmers Routes
Route::get('/dashboard-farmer', Dashboard::class)->middleware(['auth:sanctum', /*'verified', 'role:farmer'*/])->name('dashboard-farmer');
Route::get('/reports-farmer', ReportsFarmer::class)->middleware(['auth:sanctum', /*'verified', 'role:farmer'*/])->name('reports-farmer');
Route::get('/reports-farmer-preview', function () {
    $farmer = (new FarmerController)->getFarmerData(auth()->user()->id, true);
    $deliveries = (new FarmerController)->getFarmersDeliveries(auth()->user()->id, 0);

    $total_amount = 0;
    $total_milk_amount = 0;

    foreach ($deliveries as $delivery) {
        $total_amount += $delivery->milk_capacity * $delivery->rate;
        $total_milk_amount += $delivery->milk_capacity;
    }

    $data = [
        'title' => "Farmer's Report",
        'farmer' => $farmer,
        'deliveries' => $deliveries,
        'total_amount' => $total_amount,
        'total_milk_amount' => $total_milk_amount,
    ];

    return view('report-preview', $data);
})->middleware(['auth:sanctum', /*'verified', 'role:farmer'*/])->name('reports-farmer-preview');
Route::get('/reports-farmer-download', [ReportsFarmer::class, 'download'])->middleware(['auth:sanctum', /*'verified', 'role:farmer'*/])->name('reports-farmer-download');
Route::get('/dashboard-farmer-data', [Dashboard::class, 'getApiData'])->middleware(['auth:sanctum', /*'verified', 'role:farmer'*/])->name('dashboard-farmer-data');

// Generate PDF
Route::get("generate-pdf", [PDFController::class, "generatePDF"]);

