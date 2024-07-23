<?php

use App\Http\Controllers\User\CreateUserController;
use App\Http\Controllers\User\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::get('user/create', [CreateUserController::class, '__invoke']);
Route::get('user/update/{id}', [UpdateUserController::class, '__invoke']);
