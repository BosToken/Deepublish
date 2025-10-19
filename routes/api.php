<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware(['auth'])->group(function () {

    Route::prefix('task')->group(function () {
        Route::get('/', [ApiController::class, 'getTask'])->name('api.get.task');
        Route::get('/{id}', [ApiController::class, 'getByIdTask'])->name('api.getId.task');
        Route::post('/', [ApiController::class, 'storeTask'])->name('api.store.task');
        Route::put('/{id}', [ApiController::class, 'updateTask'])->name('api.update.task');
        Route::delete('/{id}', [ApiController::class, 'deleteTask'])->name('api.delete.task');
    });

    Route::prefix('user')->group(function () {
       Route::get('/', [ApiController::class, 'getUser'])->name('api.get.user');
       Route::get('/{id}', [ApiController::class, 'getByIdUser'])->name('api.getId.user');
       Route::post('/', [ApiController::class, 'storeUser'])->name('api.create.user');
       Route::put('/{id}', [ApiController::class, 'updateUser'])->name('api.update.user');
       Route::delete('/{id}', [ApiController::class, 'deleteUser'])->name('api.delete.user');
    });

    Route::prefix('role')->group(function () {
        Route::get('/', [ApiController::class, 'getRole'])->name('api.get.role');
    });
});
