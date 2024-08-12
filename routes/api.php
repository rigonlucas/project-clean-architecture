<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        require __DIR__ . '/Api/V1/user.php';
    });


