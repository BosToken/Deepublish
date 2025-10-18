<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard.admin');

    Route::prefix('task')->group(function () {
        Route::get('/', [AdminController::class, 'taskIndex'])->name('task.admin');
        Route::get('add', [AdminController::class, 'taskCreate'])->name('task.create.admin');
        Route::post('add', [AdminController::class, 'taskStore'])->name('task.store.admin');
        Route::get('edit/{id}', [AdminController::class, 'taskEdit'])->name('task.edit.admin');
        Route::post('edit/{id}', [AdminController::class, 'taskUpdate'])->name('task.update.admin');
        Route::get('delete/{id}', [AdminController::class, 'taskDelete'])->name('task.delete.admin');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [AdminController::class, 'userIndex'])->name('user.admin');
        Route::get('add', [AdminController::class, 'userCreate'])->name('user.create.admin');
        Route::post('add', [AdminController::class, 'userStore'])->name('user.store.admin');
        Route::get('edit/{id}', [AdminController::class, 'userEdit'])->name('user.edit.admin');
        Route::post('edit/{id}', [AdminController::class, 'userUpdate'])->name('user.update.admin');
        Route::get('delete/{id}', [AdminController::class, 'userDelete'])->name('user.delete.admin');

        Route::get('search', [AdminController::class, 'userSearch'])->name('user.search.admin');
    });
});
