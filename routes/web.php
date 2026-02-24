<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ManagerAuthController;
use App\Http\Controllers\Auth\DriverAuthController;

use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;

use App\Http\Controllers\Manager\OrderController as ManagerOrderController;
use App\Http\Controllers\Manager\DriverController as ManagerDriverController;

use App\Http\Controllers\Driver\DashboardController;
use App\Http\Controllers\Driver\HistoryController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ✅ customer 一进来先 login/register
    // 如果已登录：按 role 导去各自 dashboard
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Customer Booking (must login first)
|--------------------------------------------------------------------------
| Customer 必须登录后才能看到 booking page + submit
*/
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/book', [BookingController::class, 'create'])->name('customer.book');
    Route::post('/book', [BookingController::class, 'store'])->name('customer.book.store');
});

/*
|--------------------------------------------------------------------------
| Separate Login Pages
|--------------------------------------------------------------------------
| 各角色独立登录页面
*/
Route::middleware('guest')->group(function () {
    // Admin login
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

    // Manager login
    Route::get('/manager/login', [ManagerAuthController::class, 'create'])->name('manager.login');
    Route::post('/manager/login', [ManagerAuthController::class, 'store'])->name('manager.login.store');

    // Driver login + register
    Route::get('/driver/login', [DriverAuthController::class, 'create'])->name('driver.login');
    Route::post('/driver/login', [DriverAuthController::class, 'store'])->name('driver.login.store');

    Route::get('/driver/register', [DriverAuthController::class, 'registerForm'])->name('driver.register');
    Route::post('/driver/register', [DriverAuthController::class, 'register'])->name('driver.register.store');
});

/*
|--------------------------------------------------------------------------
| Post-login redirect
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/home', function () {
    $role = (string) (auth()->user()->role ?? 'user');

    return match ($role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'manager' => redirect()->route('manager.dashboard'),
        'driver'  => redirect()->route('driver.dashboard'),
        default   => redirect()->route('customer.home'),
    };
})->name('home');


/*
|--------------------------------------------------------------------------
| Role dashboards
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // ✅ 你说 driver 不用 approve：先移除 approvals 路由，避免报错
        // (以后要恢复审批，再加回来)
    });

Route::prefix('manager')
    ->name('manager.')
    ->middleware(['auth', 'role:manager'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');

        Route::get('/orders', [ManagerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [ManagerOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/assign', [ManagerOrderController::class, 'assign'])->name('orders.assign');

        Route::get('/drivers', [ManagerDriverController::class, 'index'])->name('drivers.index');
    });

Route::prefix('driver')
    ->name('driver.')
    ->middleware(['auth', 'role:driver'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::patch('/orders/{order}/status', [DashboardController::class, 'updateStatus'])
            ->name('orders.status');

        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{order}', [HistoryController::class, 'show'])->name('history.show');
    });

Route::prefix('app')
    ->name('customer.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/home', HomeController::class)->name('home');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // ✅ add

    });

/*
|--------------------------------------------------------------------------
| Profile (keep)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Breeze default auth routes (customer /login /register)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
