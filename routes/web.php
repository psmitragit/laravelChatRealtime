<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';


Route::get('/chat', [GeneralController::class, 'chat'])->middleware(['auth', 'verified']);
Route::get('/group-chat', [GeneralController::class, 'groupChat'])->middleware(['auth', 'verified']);
