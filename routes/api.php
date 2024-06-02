<?php

// use App\Http\Controllers\Apis\Todos\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Auth\AuthController;
use App\Http\Controllers\Apis\Todos\TodoController;

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

Route::get('/api-test', function () {
    return ['status'=>'Welcome to API End Point'];
});

Route::post('/register', [AuthController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('todos', TodoController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
