<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminDonationController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Controllers\AdminVisitRequestController;
use App\Http\Controllers\AdminCampaignController;

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');

// Static View Placeholders
Route::view('/kunjungan', 'placeholder')->name('kunjungan');
Route::view('/hubungi-kami', 'placeholder')->name('hubungi-kami');

// Donation Routes
Route::controller(DonationController::class)->group(function () {
    Route::get('/donasi', 'index')->name('donasi');
    Route::post('/donasi', 'store')->name('donasi.store');
});

// Kegiatan & Request Kunjungan
Route::get('/kegiatan', [VisitRequestController::class, 'index'])->name('kegiatan');
Route::post('/kegiatan/request', [VisitRequestController::class, 'store'])
    ->middleware('auth')
    ->name('kunjungan.store');

use App\Http\Controllers\UserHistoryController;

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// User Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-saya', [UserHistoryController::class, 'index'])->name('user.history');
});

use App\Http\Controllers\AdminUserController;

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/users', 'index')->name('users');
        Route::post('/users', 'store')->name('users.store');
        Route::put('/users/{user}', 'update')->name('users.update');
        Route::post('/users/{user}/delete', 'destroy')->name('users.destroy');
    });

    Route::controller(AdminDonationController::class)->group(function () {
        Route::get('/donasi', 'index')->name('donasi');
        Route::post('/donasi/{donation}/approve', 'approve')->name('donasi.approve');
        Route::post('/donasi/{donation}/reject', 'reject')->name('donasi.reject');
    });

    Route::controller(AdminVisitRequestController::class)->group(function () {
        Route::get('/kegiatan', 'index')->name('kegiatan');
        Route::post('/kegiatan/internal', 'storeInternal')->name('kegiatan.storeInternal');
        Route::post('/kegiatan/{visit}/approve', 'approve')->name('kegiatan.approve');
        Route::post('/kegiatan/{visit}/reject', 'reject')->name('kegiatan.reject');
    });

    Route::controller(AdminCampaignController::class)->group(function () {
        Route::get('/campaigns', 'index')->name('campaigns.index');
        Route::post('/campaigns', 'store')->name('campaigns.store');
        Route::put('/campaigns/{campaign}', 'update')->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', 'destroy')->name('campaigns.destroy');
    });
});
