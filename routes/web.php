<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\TabulationController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (no login required)
|--------------------------------------------------------------------------
*/
Route::get('/',        [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (must be logged in)
|--------------------------------------------------------------------------
| All routes inside this group require the user to be authenticated.
| If not logged in, Laravel redirects them to the login page.
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard',    [DashboardController::class,   'index'])->name('dashboard');
    Route::get('/contestants',  [ContestantController::class,  'index'])->name('contestants.index');
    Route::get('/scoring',      [ScoreController::class,       'index'])->name('scoring.index');
    Route::post('/scoring',     [ScoreController::class,       'store'])->name('scoring.store');
    Route::get('/leaderboard',  [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/tabulation',   [TabulationController::class,  'index'])->name('tabulation.index');

});
