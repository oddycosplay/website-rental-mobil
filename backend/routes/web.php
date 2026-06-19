<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

// Home
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Cars
Route::get('/cars', \App\Livewire\CarCatalog::class)->name('cars.index');
Route::get('/cars/{slug}', \App\Livewire\CarDetail::class)->name('cars.show');

// Cart
Route::get('/cart', \App\Livewire\Cart::class)->name('cart');

// Checkout
Route::get('/checkout/{car:slug?}', \App\Livewire\Checkout::class)->name('checkout');

// Invoice / Ringkasan Tagihan
Route::get('/invoice/{code}', [\App\Http\Controllers\Admin\BookingController::class, 'invoice'])->name('invoice');

// Static Pages
Route::view('/offline', 'offline')->name('offline');
Route::get('/about', function () {
    $stores = \App\Models\Store::query()->where('status', true)->get();
    return view('about', compact('stores'));
})->name('about');
Route::get('/faq',     fn() => view('faq'))->name('faq');
Route::get('/contact', function () {
    $stores = \App\Models\Store::query()->where('status', true)->get();
    return view('contact', compact('stores'));
})->name('contact');
Route::post('/contact', function(\Illuminate\Http\Request $req) {
    $req->validate(['name'=>'required','phone'=>'required','message'=>'required']);
    return back()->with('success', 'Pesan Anda telah terkirim! Tim kami akan segera menghubungi Anda.');
})->name('contact.send');

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        if (auth()->user()->hasRole('customer')) {
            return view('customer.dashboard');
        }
        return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    })->name('dashboard');

    Route::middleware(['role:owner|super-admin|admin|finance'])->name('admin.')->group(function () {
        Route::post('cars/bulk-update', [\App\Http\Controllers\Admin\CarController::class, 'bulkUpdate'])->name('cars.bulk-update');
        Route::resource('cars', \App\Http\Controllers\Admin\CarController::class);

        // Finance Module
        Route::get('/finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/payments', [\App\Http\Controllers\Admin\FinanceController::class, 'payments'])->name('finance.payments');
        Route::post('/finance/payments/sync', [\App\Http\Controllers\Admin\FinanceController::class, 'syncMidtrans'])->name('payments.sync');
        Route::get('/finance/payments/export', [\App\Http\Controllers\Admin\FinanceController::class, 'export'])->name('payments.export');
        Route::get('/finance/payments/{payment}', [\App\Http\Controllers\Admin\FinanceController::class, 'showPayment'])->name('payments.show');
        Route::resource('expenses', \App\Http\Controllers\Admin\ExpenseController::class);
        Route::resource('expense-categories', \App\Http\Controllers\Admin\ExpenseCategoryController::class);

        // Operational Module
        Route::get('/car-schedules', [\App\Http\Controllers\Admin\CarScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/tracking', function() {
            abort(403, 'Fitur Pelacakan GPS Langsung sedang ditangguhkan sementara.');
        })->name('tracking.index');
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
        Route::post('/bookings/{booking}/approve', [\App\Http\Controllers\Admin\BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
        Route::group(['middleware' => function ($request, $next) {
            abort(403, 'Fitur Pemeliharaan Mobil sedang ditangguhkan sementara.');
        }], function() {
            Route::resource('maintenances', \App\Http\Controllers\Admin\MaintenanceController::class);
        });
        Route::get('/car-inspections', [\App\Http\Controllers\Admin\CarInspectionController::class, 'index'])->name('car-inspections.index');
        Route::post('/car-inspections', [\App\Http\Controllers\Admin\CarInspectionController::class, 'store'])->name('car-inspections.store');
        Route::get('/car-brands', fn() => redirect()->route('admin.cars.index'))->name('car-brands.index');
        Route::get('/car-types', fn() => redirect()->route('admin.cars.index'))->name('car-types.index');
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');

        // Reports Module (Owner & Finance Only)
        Route::middleware(['role:owner|finance|super-admin'])->group(function() {
            Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/bookings', [\App\Http\Controllers\Admin\ReportController::class, 'bookings'])->name('reports.bookings');
            Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
            Route::get('/reports/analytics', [\App\Http\Controllers\Admin\ReportController::class, 'analytics'])->name('reports.analytics');
            
            // Simulator
            Route::get('/finance/simulator', [\App\Http\Controllers\Admin\MidtransSimulatorController::class, 'view'])->name('finance.simulate');
            Route::post('/finance/simulator', [\App\Http\Controllers\Admin\MidtransSimulatorController::class, 'simulate'])->name('finance.simulate.post');
        });

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Panel Routes (Digantikan oleh Filament)
/*
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',           [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cars',       fn() => view('admin.cars.index'))->name('cars');
    Route::get('/bookings',   fn() => view('admin.bookings.index'))->name('bookings');
    Route::get('/customers',  fn() => view('admin.customers.index'))->name('customers');
    Route::get('/payments',   fn() => view('admin.payments.index'))->name('payments');
    Route::get('/drivers',    fn() => view('admin.drivers.index'))->name('drivers');
    Route::get('/reports',    fn() => view('admin.reports.index'))->name('reports');
    Route::get('/users',      fn() => view('admin.users.index'))->name('users');
});
*/

require __DIR__.'/auth.php';

Route::get('/run-test', function() {
    $path = base_path('scratch/full_e2e_test.php');
    if (file_exists($path)) {
        ob_start();
        include $path;
        return response(ob_get_clean(), 200)->header('Content-Type', 'text/plain');
    }
    return 'Test script not found.';
});

Route::get('/fix-login', function () {
    try {
        // 1. Bersihkan cache konfigurasi agar membaca .env MySQL yang baru
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        // 2. Cari atau buat user admin
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@siliwangi.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        
        // 3. Pastikan user punya role super-admin
        if (!$user->hasRole('super-admin')) {
            // Pastikan role super-admin ada
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
            $user->assignRole('super-admin');
        }
        
        // 4. Login paksa
        \Illuminate\Support\Facades\Auth::login($user);
        
        // 5. Arahkan ke dashboard admin
        return "User berhasil disiapkan dan login. <a href='" . url('/admin') . "'>Klik di sini untuk ke Dashboard Admin</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
