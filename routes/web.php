<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/top-news', [HomeController::class, 'guestTopNews'])->name('guestNews.top');
Route::get('/sports-news', [HomeController::class, 'guestSportsNews'])->name('guestNews.sports');
Route::get('/entertainment-news', [HomeController::class, 'guestEntertainmentNews'])->name('guestNews.entertainment');

Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/prefered-news', [DashboardController::class, 'getPreferedNews'])->middleware(['auth', 'verified'])->name('getPreferedNews');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/{id}', [ProfileController::class, 'addUserPreference'])->name('profile.addPreference');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{id}/delete-preference', [ProfileController::class, 'deleteUserPreference'])->name('profile.deletePreference');
});

require __DIR__ . '/auth.php';
