<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routen für Registrierung und Login - **ohne Auth-Middleware**
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Geschützte Routen (mit Auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $req) => $req->user());

    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
    Route::get('/tickets/{id}', [TicketApiController::class, 'show']);
    Route::put('/tickets/{id}', [TicketApiController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketApiController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
