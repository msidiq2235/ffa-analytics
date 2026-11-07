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

    // Fitur Switch Role (Khusus Super Admin)
    Route::post('/switch-role', function (Request $request) {
        $request->validate([
            'active_role' => 'required|string|in:Super Admin,Pelatih,Analis,Pemain'
        ]);

        if (auth()->user()->role === 'Super Admin') {
            session(['active_role' => $request->active_role]);
        }

        return back();
    })->name('switch.role');

    // Modul Live Analytics & Line-up
    Route::get('/matches/{match}/lineup', [MatchController::class, 'lineup'])->name('matches.lineup');
    Route::match(['post', 'put'], '/matches/{match}/lineup', [MatchController::class, 'updateLineup'])->name('matches.updateLineup');
    Route::match(['post', 'put'], '/matches/{match}/lineup-update', [MatchController::class, 'updateLineup'])->name('matches.lineup.update');

    Route::get('/matches/{match}/live', [MatchController::class, 'live'])->name('matches.live');
    Route::post('/matches/{match}/statistics', [MatchController::class, 'storeStatistic'])->name('matches.storeStatistic');
    Route::post('/matches/{match}/lock', [MatchController::class, 'lockMatch'])->name('matches.lock');
    Route::post('/matches/{match}/vectors', [MatchController::class, 'storeVector'])->name('matches.storeVector');
    Route::get('/matches/{match}/vector', [MatchController::class, 'vector'])->name('matches.vector');
    Route::get('/matches/{match}/player-stats/{player}', [MatchController::class, 'getPlayerStats'])->name('matches.getPlayerStats');

    // Modul Export Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index'); 
    Route::get('/reports/matches/{match}', [ReportController::class, 'showMatchReport'])->name('reports.match.show');
    Route::get('/reports/matches/{match}/pdf', [ReportController::class, 'exportMatchPdf'])->name('reports.match.pdf');

    Route::get('/reports/players/{player}', [ReportController::class, 'showPlayerReport'])->name('reports.player.show');
    Route::get('/reports/players/{player}/matches/{matchId}/pdf', [ReportController::class, 'exportPlayerMatchPdf'])->name('reports.player.match.pdf');
    Route::get('/reports/players/{player}/seasons/{season}/pdf', [ReportController::class, 'exportPlayerSeasonPdf'])
        ->where('season', '[0-9]{4}')
        ->name('reports.player.season.pdf');
    

    // Modul Performa (daftar pemain saja, detail dialihkan ke Export Laporan)
    Route::get('/performa', [PlayerController::class, 'performance'])->name('performa.index');
    Route::get('/performa/{player}', function ($player) {
        return redirect()->route('reports.player.show', $player);
    })->name('performa.show');

    // Modul CRUD Master & Transaksi
    // Rute kustom untuk bulk-destroy harus diletakkan SEBELUM resource route 'users' 
    // agar Laravel tidak menganggap kata 'bulk-destroy' sebagai parameter ID ({user}).
    Route::post('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    
    Route::resource('users', UserController::class);
    Route::resource('players', PlayerController::class);
    Route::resource('clubs', ClubController::class);
    Route::resource('matches', MatchController::class);
});