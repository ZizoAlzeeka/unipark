<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParkingMapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ── Landing Page ──
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('landing');
})->name('home');

// ── Authentication Routes ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendVerifyEmail'])->name('verification.resend');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── User Routes ──
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Parking Map
    Route::get('/parking-map', [ParkingMapController::class, 'index'])->name('parking-map');
    Route::get('/api/spots', [ParkingMapController::class, 'getSpots'])->name('api.spots');
    Route::get('/api/zones', [ParkingMapController::class, 'getZones'])->name('api.zones');
    Route::get('/api/spots/{spot}', [ParkingMapController::class, 'getSpotDetails'])->name('api.spots.detail');

    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/history', [ReservationController::class, 'history'])->name('reservations.history');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/api/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.count');
    Route::get('/api/notifications/latest', [NotificationController::class, 'getLatest'])->name('api.notifications.latest');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ── Admin Routes ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', Admin\UserController::class);
    Route::post('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Parking Zones
    Route::resource('zones', Admin\ParkingZoneController::class);

    // Parking Spots
    Route::resource('spots', Admin\ParkingSpotController::class);
    Route::post('/spots/bulk-create', [Admin\ParkingSpotController::class, 'bulkCreate'])->name('spots.bulk-create');

    // Reservations
    Route::get('/reservations', [Admin\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [Admin\ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [Admin\ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/reservations/{reservation}/complete', [Admin\ReservationController::class, 'complete'])->name('reservations.complete');

    // Entry / Exit Logs
    Route::get('/entry-exit', [Admin\EntryExitController::class, 'index'])->name('entry-exit.index');
    Route::get('/entry-exit/create', [Admin\EntryExitController::class, 'create'])->name('entry-exit.create');
    Route::post('/entry-exit', [Admin\EntryExitController::class, 'store'])->name('entry-exit.store');

    // Violations
    Route::get('/violations', [Admin\ViolationController::class, 'index'])->name('violations.index');
    Route::get('/violations/create', [Admin\ViolationController::class, 'create'])->name('violations.create');
    Route::post('/violations', [Admin\ViolationController::class, 'store'])->name('violations.store');
    Route::get('/violations/{violation}', [Admin\ViolationController::class, 'show'])->name('violations.show');
    Route::post('/violations/{violation}/resolve', [Admin\ViolationController::class, 'resolve'])->name('violations.resolve');
    Route::post('/violations/{violation}/dismiss', [Admin\ViolationController::class, 'dismiss'])->name('violations.dismiss');
    Route::post('/violations/{violation}/fine-paid', [Admin\ViolationController::class, 'markFinePaid'])->name('violations.fine-paid');

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [Admin\ReportController::class, 'exportCsv'])->name('reports.export');

    // Permissions
    Route::get('/permissions', [Admin\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions', [Admin\PermissionController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/toggle', [Admin\PermissionController::class, 'toggle'])->name('permissions.toggle');
});
