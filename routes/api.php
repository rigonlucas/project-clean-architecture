<?php

use App\Http\Controllers\V1\Project\CreateProjectController;
use App\Http\Controllers\V1\Project\UpdateProjectController;
use App\Http\Controllers\V1\User\ChangeUserRoleController;
use App\Http\Controllers\V1\User\CreateUserController;
use App\Http\Controllers\V1\User\ShowUserController;
use App\Http\Controllers\V1\User\UpdateUserController;
use App\Http\Controllers\V1\User\UserListFromAccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('v1/register', [CreateUserController::class, '__invoke'])
    ->name('api.v1.user.create');
Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/auth', function (Request $request) {
                return $request;
            });
            Route::get('/show/{uuid}', [ShowUserController::class, '__invoke'])
                ->whereUuid('uuid')
                ->name('api.v1.user.show');
            Route::put('/user/update/{uuid}', [UpdateUserController::class, '__invoke'])
                ->whereUuid('uuid')
                ->name('api.v1.user.update');
            Route::patch('/user/change-role/{uuid}', [ChangeUserRoleController::class, '__invoke'])
                ->whereUuid('uuid')
                ->name('api.v1.user.change-role');
            Route::get('/user/list', [UserListFromAccountController::class, '__invoke'])
                ->name('api.v1.user.list');
        });
        Route::prefix('project')->group(function () {
            Route::post('/create', [CreateProjectController::class, '__invoke'])
                ->name('api.v1.project.create');
            Route::put('/update/{uuid}', [UpdateProjectController::class, '__invoke'])
                ->whereUuid('uuid')
                ->name('api.v1.project.update');
        });
    });


