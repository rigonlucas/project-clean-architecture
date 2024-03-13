<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(\App\Models\User::all());
    return view('welcome');
});
