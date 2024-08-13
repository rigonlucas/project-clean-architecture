<?php

use App\Http\Controllers\V1\User\ShowUserController;
use App\Http\Controllers\V1\User\UpdateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/auth', function (Request $request) {
        return $request;
    });
    Route::get('/show/{uuid}', [ShowUserController::class, '__invoke'])
        ->whereUuid('uuid')
        ->name('v1.user.show');
    Route::put('/user/update/{uuid}', [UpdateUserController::class, '__invoke'])
        ->whereUuid('uuid')
        ->name('v1.user.update');
});