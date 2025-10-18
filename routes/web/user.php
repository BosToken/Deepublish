<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard.user');
    Route::get('profile', [UserController::class, 'profile'])->name('profile.user');
    Route::prefix('profile')->group(function () {
        Route::get('update', [UserController::class, 'editProfile'])->name('profile.edit.user');
        Route::post('update', [UserController::class, 'updateProfile'])->name('profile.update.user');
    });
    Route::prefix('task')->middleware(['auth'])->group(function () {
        Route::get('{id}', [UserController::class, 'taskDetail'])->name('task.user');
        Route::get('update/{id}', [UserController::class, 'editTask'])->name('task.edit.user');
        Route::post('update/{id}', [UserController::class, 'updateTask'])->name('task.update.user');
    });
});
