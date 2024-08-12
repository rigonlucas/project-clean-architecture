<?php

use App\Http\Controllers\V1\User\CreateUserController;
use App\Http\Controllers\V1\User\ShowUserController;
use App\Http\Controllers\V1\User\UpdateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register', [CreateUserController::class, '__invoke'])
    ->name('v1.user.create');
Route::prefix('user')->group(function () {
    Route::get('/auth', function (Request $request) {
        return $request;
    });
    Route::get('/show', [ShowUserController::class, '__invoke'])
        ->name('v1.user.show');
    Route::put('/user/update', [UpdateUserController::class, '__invoke'])
        ->name('v1.user.update');
});