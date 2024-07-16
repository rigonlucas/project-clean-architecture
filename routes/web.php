<?php

use App\Http\Controllers\User\CreateUser;
use Illuminate\Support\Facades\Route;

Route::get('user/create', [CreateUser::class, '__invoke']);
