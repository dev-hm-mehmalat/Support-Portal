<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile-Routen bleiben unverändert
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets-Routen mit Rollen-Middleware
    Route::group(['middleware' => ['role:user']], function () {
        Route::resource('tickets', TicketController::class)->only(['create', 'store', 'index', 'show', 'edit', 'update']);
        // User dürfen Tickets erstellen, anschauen und bearbeiten (eigene)
    });

    Route::group(['middleware' => ['role:support|admin']], function () {
        Route::resource('tickets', TicketController::class)->except(['destroy']);
        // Support & Admin dürfen alle Tickets verwalten außer löschen
    });

    Route::group(['middleware' => ['role:admin']], function () {
        // Admin-only Routen, z.B. Benutzerverwaltung
        // Route::resource('users', UserController::class); 
    });
});

require __DIR__.'/auth.php';
