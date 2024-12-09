<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Auth\DepartmentAuthController;
use App\Http\Controllers\DepartmentDashboardController;
use App\Http\Controllers\DepartmentRequestController;
use App\Http\Controllers\MaintenanceRequestController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('admin.login');
})->name('login');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');

// Admin authentication routes
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Items routes
    Route::resource('items', ItemController::class);
    Route::post('/items/bulk-delete', [ItemController::class, 'bulkDelete'])->name('items.bulk-delete');
    
    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    
    // Settings route
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    
    // Stock management routes
    Route::post('/items/{item}/stock-in', [ItemController::class, 'stockIn'])->name('items.stock-in');
    Route::post('/items/{item}/stock-out', [ItemController::class, 'stockOut'])->name('items.stock-out');
    Route::post('/items/{item}/increment', [ItemController::class, 'increment'])->name('items.increment');
    Route::post('/items/{item}/decrement', [ItemController::class, 'decrement'])->name('items.decrement');
    
    // Add inventory scanning route
    Route::get('/inventory/scan', [ItemController::class, 'showScanForm'])->name('inventory.scan');
    Route::post('/inventory/scan', [ItemController::class, 'processScan'])->name('inventory.scan.process');
    
    // Staff management routes 
    Route::resource('staff', StaffController::class);

    // Department routes
    Route::resource('departments', DepartmentController::class);
    Route::get('/departments/{department}/check-head', [DepartmentController::class, 'checkHead'])->name('departments.check-head');
});

Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');

// Department Routes
Route::get('/department/login', [DepartmentAuthController::class, 'showLoginForm'])->name('department.login');
Route::post('/department/login', [DepartmentAuthController::class, 'login']);
Route::post('/department/logout', [DepartmentAuthController::class, 'logout'])->name('department.logout');

// Protected Department Routes
Route::middleware(['auth:department'])->prefix('department')->name('department.')->group(function () {
    Route::get('/dashboard', [DepartmentDashboardController::class, 'index'])->name('dashboard');
    Route::post('/requests', [DepartmentRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests', [DepartmentRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [DepartmentRequestController::class, 'create'])->name('requests.create');
    Route::get('/requests/{request}', [DepartmentRequestController::class, 'show'])->name('requests.show');
    Route::put('/requests/{request}', [DepartmentRequestController::class, 'update'])->name('requests.update');
    Route::delete('/requests/{request}', [DepartmentRequestController::class, 'destroy'])->name('requests.destroy');


    // Maintenance Routes
    Route::resource('department/maintenance_requests', MaintenanceRequestController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/api/items', [RequestController::class, 'getItems'])->name('api.items');
});
