<?php
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;



// Startseite
Route::get('/', function () {
    return view('welcome');
});

// Authentifizierte Routen mit Rollen- und Berechtigungssteuerung
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil-Routen
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets: Zugriff für user, support UND admin (alle dürfen Tickets sehen/erstellen/bearbeiten, aber NUR admin darf löschen)
    Route::group(['middleware' => ['role:user|support|admin']], function () {
        Route::resource('tickets', TicketController::class)
            ->except(['destroy']);
    });

    // Nur admin darf Tickets löschen (destroy)
    Route::group(['middleware' => ['role:admin']], function () {
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        // Weitere Admin-only-Routen hier...
    });
});

require __DIR__.'/auth.php';
