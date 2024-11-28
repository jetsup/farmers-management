<?php

use App\Http\Controllers\CowBreedsController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MilkDeliveryController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\FarmerAuth;
use App\Http\Middleware\UndecidedAuth;
use App\Livewire\BecomeFarmer;
use App\Livewire\CowBreedsAdmin;
use App\Livewire\CowsFarmer;
use App\Livewire\Dashboard;
use App\Livewire\FarmersAdmin;
use App\Livewire\HomeIndex;
use App\Livewire\IndexAdmin;
use App\Livewire\MilkRatesAdmin;
use App\Livewire\MilkReceptionAdmin;
use App\Livewire\NotificationsAdmin;
use App\Livewire\RecordsAdmin;
use App\Livewire\ReportsFarmer;
use App\Livewire\UsersAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index')->middleware('guest');

Route::get('/home', HomeIndex::class)->name('home');//->middleware(UndecidedAuth::class);
Route::get('/email-preview', function(){
    return view('emails.contact-us',[
        'sender_name' => 'John Doe',
        'sender_email' => 'john@gmail.com',
        'sender_message' => 'Hello, I am John Doe. I would like to know more about your services.'
    ]);
})->name('email-preview')->middleware(UndecidedAuth::class);
Route::get('/register-farmer', BecomeFarmer::class)->name('become-farmer')->middleware(UndecidedAuth::class);
Route::post('/register-farmer', [FarmerController::class, "registerFarmer"])->name('become-farmer')->middleware(UndecidedAuth::class);

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
Route::get('/milk-reception-admin', MilkReceptionAdmin::class)->middleware(['auth:sanctum', AdminAuth::class])->name('milk-reception-admin');
Route::post('/milk-reception-admin', [MilkDeliveryController::class, 'receiveMilk'])->middleware(['auth:sanctum', AdminAuth::class,])->name('milk-reception-admin');
Route::get('/dashboard-admin', IndexAdmin::class)->middleware(['auth:sanctum', AdminAuth::class,])->name('dashboard-admin');
Route::get('/dashboard-admin-data', [IndexAdmin::class, 'getApiData'])->middleware(['auth:sanctum', AdminAuth::class,])->name('dashboard-admin-data');
Route::get('/farmers-admin', FarmersAdmin::class)->middleware(['auth:sanctum', AdminAuth::class])->name('farmers-admin');
Route::get('/records-admin', RecordsAdmin::class)->middleware(['auth:sanctum', AdminAuth::class])->name('records-admin');
Route::get('/cow-breeds-admin', CowBreedsAdmin::class)->middleware(['auth:sanctum', AdminAuth::class,])->name('cow-breeds-admin');
Route::post('/cow-breeds-admin', [CowBreedsController::class, 'createBreed'])->middleware(['auth:sanctum', AdminAuth::class,])->name('cow-breeds-admin');
Route::get('/milk-rates-admin', MilkRatesAdmin::class)->middleware(['auth:sanctum', AdminAuth::class,])->name('milk-rates-admin');
Route::get('/notifications-admin', NotificationsAdmin::class)->middleware(['auth:sanctum', AdminAuth::class])->name('notifications-admin');
Route::get('/users-admin', UsersAdmin::class)->middleware(['auth:sanctum', AdminAuth::class,])->name('users-admin');
Route::get('/convert-user/{userID}', [UserController::class, 'convertUser'])->middleware(['auth:sanctum', AdminAuth::class,])->name('convert-user-admin');

// Farmers Routes
Route::get('/dashboard-farmer', Dashboard::class)->middleware(['auth:sanctum', FarmerAuth::class])->name('dashboard-farmer');
Route::get('/reports-farmer', ReportsFarmer::class)->middleware(['auth:sanctum', FarmerAuth::class])->name('reports-farmer');
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
})->middleware(['auth:sanctum', FarmerAuth::class])->name('reports-farmer-preview');
Route::get('/reports-farmer-download', [ReportsFarmer::class, 'download'])->middleware(['auth:sanctum', FarmerAuth::class])->name('reports-farmer-download');
Route::get('/dashboard-farmer-data', [Dashboard::class, 'getApiData'])->middleware(['auth:sanctum', FarmerAuth::class])->name('dashboard-farmer-data');
Route::get('/cows-farmer', CowsFarmer::class)->middleware(['auth:sanctum', FarmerAuth::class])->name('cows-farmer');

// Generate PDF
Route::get("generate-pdf", [PDFController::class, "generatePDF"]);

