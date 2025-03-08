<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('/users', UserController::class);
Route::get('/get-data', [UserController::class, 'getData']);

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');