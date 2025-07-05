<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
    Route::get('/tickets/{id}', [TicketApiController::class, 'show']);
    Route::put('/tickets/{id}', [TicketApiController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketApiController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
