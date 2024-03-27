<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Back\Auth\AuthController;
use App\Http\Controllers\Back\TaskController;
use App\Http\Controllers\Back\CommentController;
use App\Http\Controllers\Back\AttachmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('cors')->group(function () {

    // Autenticación
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/register', [AuthController::class, 'register']);

    // Tareas
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'create']);
    Route::delete('/tasks/{id}', [TaskController::class, 'delete']);
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);

    // Comentarios
    Route::post('/comments', [CommentController::class, 'create']);
    Route::delete('/comments/{id}', [CommentController::class, 'delete']);

    // Archivos adjuntos
    Route::post('/attachments', [AttachmentController::class, 'upload']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'delete']);
});



