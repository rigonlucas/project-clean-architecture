<?php

use App\Http\Controllers\V1\User\CreateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/register', [CreateUserController::class, '__invoke'])
        ->name('v1.user.create');
});


