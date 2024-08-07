<?php

use App\Http\Controllers\V1\User\CreateUserController;
use App\Http\Controllers\V1\User\UpdateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('v1/user/register', [CreateUserController::class, '__invoke'])
    ->name('v1.user.create');
Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/user', function (Request $request) {
            return $request;
        });


        Route::put('/user/update', [UpdateUserController::class, '__invoke'])
            ->name('v1.user.update');
    });


