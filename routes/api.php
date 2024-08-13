<?php

use App\Http\Controllers\V1\User\CreateUserController;
use Illuminate\Support\Facades\Route;

Route::post('v1/register', [CreateUserController::class, '__invoke'])
    ->name('v1.user.create');
Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        require __DIR__ . '/Api/V1/user.php';
    });


