<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Rute Publik (Hanya untuk proses Login/Logout)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rute Terlindungi (Wajib Login)
Route::middleware('auth')->group(function () {
    
    // Dashboard & Profil
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/reports/matches/{match}/pdf', [ReportController::class, 'exportMatchPdf'])->name('reports.match.pdf');
    
    // Fitur Switch Role (Khusus Super Admin)
    Route::post('/switch-role', function (Request $request) {
        // Validasi input untuk keamanan
        $request->validate([
            'active_role' => 'required|string|in:Super Admin,Pelatih,Analis,Pemain'
        ]);

        // Simpan hanya jika user aslinya adalah Super Admin
        if (auth()->user()->role === 'Super Admin') {
            session(['active_role' => $request->active_role]);
        }
        
        return back();
    })->name('switch.role');

    // Modul Live Analytics & Line-up (Harus diletakkan SEBELUM route resource matches)
    Route::get('/matches/{match}/lineup', [MatchController::class, 'lineup'])->name('matches.lineup');
    Route::post('/matches/{match}/lineup', [MatchController::class, 'updateLineup'])->name('matches.updateLineup');
    Route::get('/matches/{match}/live', [MatchController::class, 'live'])->name('matches.live');
    Route::post('/matches/{match}/statistics', [MatchController::class, 'storeStatistic'])->name('matches.storeStatistic');
    Route::post('/matches/{match}/lock', [MatchController::class, 'lockMatch'])->name('matches.lock');
    Route::post('/matches/{match}/vectors', [MatchController::class, 'storeVector'])->name('matches.storeVector');
    Route::get('/matches/{match}/vector', [MatchController::class, 'vector'])->name('matches.vector');
    Route::get('/matches/{match}/player-stats/{player}', [MatchController::class, 'getPlayerStats'])->name('matches.getPlayerStats');
    Route::get('/reports/matches/{match}', [ReportController::class, 'showMatchReport'])->name('reports.match.show');

    // Modul CRUD Master & Transaksi
    Route::resource('users', UserController::class);
    Route::resource('players', PlayerController::class);
    Route::resource('clubs', ClubController::class);
    Route::resource('matches', MatchController::class);
    
});