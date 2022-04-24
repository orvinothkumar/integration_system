<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->get('/', [DashboardController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified'])->get('/register', [DashboardController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/users', [UserController::class, 'index'])->name('users');
Route::middleware(['auth:sanctum', 'verified'])->get('/logs', [LogController::class, 'index'])->name('logs');
