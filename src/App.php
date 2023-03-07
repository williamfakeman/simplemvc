<?php

namespace App;

class App
{
    /**
     * Runs the application
     * @return void
     */
    public static function run(): void
    {
        Route::get('/', Controllers\TaskController::class, 'list');
        Route::get('/task/create', Controllers\TaskController::class, 'create');
        Route::post('/task/create', Controllers\TaskController::class, 'createPost');
        Route::get('/task/{id}', Controllers\TaskController::class, 'show');
        Route::get('/task/{id}/edit', Controllers\TaskController::class, 'edit');
        Route::post('/task/{id}/edit', Controllers\TaskController::class, 'editPost');
        Route::get('/task/{id}/delete', Controllers\TaskController::class, 'delete');

        Route::get('/auth/login', Controllers\AuthController::class, 'login');
        Route::post('/auth/login', Controllers\AuthController::class, 'loginPost');
        Route::get('/auth/logout', Controllers\AuthController::class, 'logout');

        Route::run();
    }
}
