<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\TodoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->middleware(['api', 'token.auth'])->group(function () {
    Route::delete('todos/all', [TodoController::class, 'destroyAll']);
    Route::apiResource('todos', TodoController::class);
});
