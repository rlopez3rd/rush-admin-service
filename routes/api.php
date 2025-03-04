<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/sign-in', [AuthController::class, 'signIn']);
        Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
    });

    Route::middleware(['auth.jwt', 'permission'])->group(function () {
        
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'delete']);
            Route::post('/delete-many', [UserController::class, 'deleteMany']);
        });
    });
});
