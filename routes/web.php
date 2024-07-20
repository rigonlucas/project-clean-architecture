<?php

use App\Http\Controllers\User\CreateUser;
use App\Http\Controllers\User\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::get('user/create', [CreateUser::class, '__invoke']);
Route::get('user/update/{id}', [UpdateUserController::class, '__invoke']);
