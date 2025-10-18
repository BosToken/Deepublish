<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;

Route::get('/', [GuestController::class, 'index'])->name('index');
Route::post('login', [GuestController::class, 'login'])->name('login');
Route::get('logout', [GuestController::class, 'logout'])->name('logout');