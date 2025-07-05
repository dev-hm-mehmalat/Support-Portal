<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Startseite
Route::get('/', function () {
    return view('welcome');
});

// Dashboard mit Tickets anzeigen (authentifiziert)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Route mit Controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil-Routen
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets-Routen für Rolle "user" (eigene Tickets)
    Route::group(['middleware' => ['role:user']], function () {
        Route::resource('tickets', TicketController::class)
            ->only(['create', 'store', 'index', 'show', 'edit', 'update']);
    });

    // Tickets-Routen für Rollen "support" und "admin" (alle Tickets verwalten, außer löschen)
    Route::group(['middleware' => ['role:support|admin']], function () {
        Route::resource('tickets', TicketController::class)
            ->except(['destroy']);
    });

    // Admin-only Routen (z.B. Benutzerverwaltung)
    Route::group(['middleware' => ['role:admin']], function () {
        // Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
