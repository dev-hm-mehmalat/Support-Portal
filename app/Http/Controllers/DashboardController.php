<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;  // <-- Hier importieren

class DashboardController extends Controller
{
    public function index()
    {
        // Alle Tickets laden (ggf. paginiert)
        $tickets = Ticket::orderBy('created_at', 'desc')->get();

        // View 'dashboard' mit Tickets aufrufen
        return view('dashboard', compact('tickets'));
    }
}

