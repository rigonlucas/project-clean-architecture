<?php

use App\Http\Controllers\V1\Project\CreateProjectController;
use App\Http\Controllers\V1\Project\File\DeleteProjectFileController;
use App\Http\Controllers\V1\Project\File\UploadProjectFileController;
use App\Http\Controllers\V1\Project\UpdateProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('project')->group(function () {
    Route::post('/create', [CreateProjectController::class, '__invoke'])
        ->name('api.v1.project.create');
    Route::put('/update/{uuid}', [UpdateProjectController::class, '__invoke'])
        ->whereUuid('uuid')
        ->name('api.v1.project.update');

    Route::group(['prefix' => 'file'], function () {
        Route::post('/{uuid}/upload', [UploadProjectFileController::class, '__invoke'])
            ->whereUuid('uuid')
            ->name('api.v1.project.file.upload');
        Route::delete('/{uuid}/delete/{fileUuid}', [DeleteProjectFileController::class, '__invoke'])
            ->whereUuid('uuid')
            ->whereUuid('fileUuid')
            ->name('api.v1.project.file.delete');
    });
});