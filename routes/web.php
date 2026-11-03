<?php

use App\Http\Controllers\Admin\KosController as AdminKosController;
use App\Http\Controllers\Admin\AiKosManagerController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\KosController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'))->name('home');

// ---------------------------------------------------------------- Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.process');

    Route::get('/lupa-password', [AuthController::class, 'showForgot'])->name('password.request');
});

// ------------------------------------------------- Pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/welcome-animation', function () {
    return view('welcome-animation');
})->name('welcome.animation');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/kampus', [KampusController::class, 'index'])->name('kampus.index');
    Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
    Route::get('/kos/{kos}', [KosController::class, 'show'])->name('kos.show');
    Route::post('/ai/chat', AiChatController::class)->middleware('throttle:20,1')->name('ai.chat');

    // ------------------------------------------------- Khusus administrator
    Route::prefix('admin')->name('admin.')->middleware('can:kelola-kos')->group(function () {
        Route::get('/peta-koordinat', [AdminKosController::class, 'coordinateDashboard'])->name('koordinat');

        // --- AI Kost Manager (pipeline data AI) ---
        // Route impor didefinisikan SEBELUM route {candidate} agar tidak tertangkap sebagai parameter.
        Route::get('/ai-kos/import', [AiKosManagerController::class, 'importForm'])->name('ai-kos.import.form');
        Route::post('/ai-kos/import', [AiKosManagerController::class, 'importPreview'])->middleware('throttle:10,1')->name('ai-kos.import.preview');
        Route::post('/ai-kos/import/konfirmasi', [AiKosManagerController::class, 'importConfirm'])->middleware('throttle:10,1')->name('ai-kos.import.confirm');
        Route::post('/ai-kos/import/batal', [AiKosManagerController::class, 'importCancel'])->name('ai-kos.import.cancel');

        Route::get('/ai-kos', [AiKosManagerController::class, 'index'])->name('ai-kos.index');
        Route::post('/ai-kos/search', [AiKosManagerController::class, 'search'])->middleware('throttle:5,1')->name('ai-kos.search');
        Route::post('/ai-kos/bulk', [AiKosManagerController::class, 'bulk'])->middleware('throttle:10,1')->name('ai-kos.bulk');
        Route::get('/ai-kos/{candidate}', [AiKosManagerController::class, 'show'])->name('ai-kos.show')->whereNumber('candidate');
        Route::post('/ai-kos/{candidate}/approve', [AiKosManagerController::class, 'approve'])->name('ai-kos.approve')->whereNumber('candidate');
        Route::post('/ai-kos/{candidate}/reject', [AiKosManagerController::class, 'reject'])->name('ai-kos.reject')->whereNumber('candidate');
        Route::delete('/ai-kos/{candidate}', [AiKosManagerController::class, 'destroy'])->name('ai-kos.destroy')->whereNumber('candidate');

        Route::resource('kos', AdminKosController::class)->parameters(['kos' => 'kos'])->except(['show']);
    });
});
