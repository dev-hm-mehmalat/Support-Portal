<?php

namespace App\Http\Controllers;

use App\Notifications\TicketStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        // Alle Routen hier brauchen Authentifizierung
        $this->middleware('auth');
    }

    // Alle Tickets anzeigen
    public function index()
    {
        $tickets = Cache::get('tickets', []);
        return view('tickets.index', compact('tickets'));
    }

    // Formular für neues Ticket anzeigen
    public function create()
    {
        return view('tickets.create');
    }

    // Ticket speichern (inkl. Dateiupload)
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'category'    => 'nullable|string|max:50',
            'priority'    => 'required|in:low,medium,high,critical',
            'reported_at' => 'nullable|date',
            'attachment'  => 'nullable|file|max:5120', // max. 5MB
        ]);

        $tickets = Cache::get('tickets', []);
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $id = count($tickets) ? max(array_column($tickets, 'id')) + 1 : 1;

        $newTicket = [
            'id'          => $id,
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'user_id'     => Auth::id(),
            'status'      => 'open',
            'reported_at' => $request->reported_at,
            'attachment'  => $attachmentPath,
            'created_at'  => now()->toDateTimeString(),
        ];

        $tickets[] = $newTicket;
        Cache::put('tickets', $tickets);

        return redirect()->route('tickets.index')->with('success', 'Ticket gespeichert!');
    }

    // Einzelnes Ticket anzeigen
    public function show($id)
    {
        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', $id);

        if (!$ticket) {
            abort(404);
        }

        return view('tickets.show', compact('ticket'));
    }

    // Bearbeiten-Formular anzeigen
    public function edit($id)
    {
        // Nur Support und Admin dürfen bearbeiten
        if (!Auth::user()->hasAnyRole(['support', 'admin'])) {
            abort(403, 'Keine Berechtigung!');
        }

        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', $id);

        if (!$ticket) {
            abort(404);
        }

        return view('tickets.edit', compact('ticket'));
    }

    // Änderungen speichern und Notification senden
    public function update(Request $request, $id)
    {
        // Rechteprüfung: Nur Support und Admin
        if (!Auth::user()->hasAnyRole(['support', 'admin'])) {
            abort(403, 'Keine Berechtigung!');
        }

        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'required',
            'category'    => 'nullable|string|max:50',
            'priority'    => 'required|in:low,medium,high,critical',
            'reported_at' => 'nullable|date',
            'status'      => 'required|in:open,in_progress,closed',
            'attachment'  => 'nullable|file|max:5120',
        ]);

        $tickets = Cache::get('tickets', []);
        $ticketIndex = collect($tickets)->search(fn($t) => $t['id'] == $id);

        if ($ticketIndex === false) {
            abort(404);
        }

        $attachmentPath = $tickets[$ticketIndex]['attachment'] ?? null;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Ticket aktualisieren
        $tickets[$ticketIndex] = array_merge($tickets[$ticketIndex], [
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'status'      => $request->status,
            'reported_at' => $request->reported_at,
            'attachment'  => $attachmentPath,
        ]);

        Cache::put('tickets', $tickets);

        // Notification senden an Ersteller
        // Hole Ersteller User anhand user_id (hier Beispiel mit User Model)
        $ticketOwner = \App\Models\User::find($tickets[$ticketIndex]['user_id']);
        if ($ticketOwner) {
            $ticketOwner->notify(new TicketStatusChanged($tickets[$ticketIndex]));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket aktualisiert!');
    }

    // Ticket löschen
    public function destroy($id)
    {
        // Nur Admin darf löschen
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Nur Admin darf löschen!');
        }

        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', $id);

        if ($ticket && !empty($ticket['attachment'])) {
            Storage::disk('public')->delete($ticket['attachment']);
        }

        $tickets = collect($tickets)->reject(fn($t) => $t['id'] == $id)->values()->all();
        Cache::put('tickets', $tickets);

        return redirect()->route('tickets.index')->with('success', 'Ticket gelöscht!');
    }
}
