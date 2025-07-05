<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Hier definierst du deine API-Endpunkte. Die here sind geschützt durch
| "auth:sanctum"-Middleware, nur authentifizierte Anfragen dürfen hierhin.
|
*/

// Authentifizierte Routen:
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $req) => $req->user());

    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
    Route::get('/tickets/{id}', [TicketApiController::class, 'show']);
    Route::put('/tickets/{id}', [TicketApiController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketApiController::class, 'destroy']);
});
