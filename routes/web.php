<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', fn() => view('auth.login'))->name('login.page');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
