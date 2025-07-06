<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketApiController extends Controller

{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    // 1. Alle Tickets anzeigen
    public function index()
    {
        if (Auth::user()->hasAnyRole(['support', 'admin'])) {
            $tickets = Ticket::latest()->get();
        } else {
            $tickets = Ticket::where('user_id', Auth::id())->latest()->get();
        }
        return view('tickets.index', compact('tickets'));
    }

    // 2. Formular für neues Ticket anzeigen
    public function create()
    {
        return view('tickets.create');
    }

    // 3. Ticket speichern
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'nullable|string|max:50',
            'priority'    => 'required|in:low,medium,high,critical',
            'reported_at' => 'nullable|date',
            'attachment'  => 'nullable|file|max:5120', // max 5MB
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        Ticket::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category'    => $validated['category'] ?? null,
            'priority'    => $validated['priority'],
            'reported_at' => $validated['reported_at'] ?? null,
            'attachment'  => $attachmentPath,
            'user_id'     => Auth::id(),
            'status'      => 'open',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket erfolgreich erstellt!');
    }

    // 4. Einzelnes Ticket anzeigen
    public function show(Ticket $ticket)
    {
        // Nur eigene Tickets sehen, außer Support/Admin
        if (!Auth::user()->hasAnyRole(['support', 'admin']) && $ticket->user_id !== Auth::id()) {
            abort(403, 'Kein Zugriff!');
        }
        return view('tickets.show', compact('ticket'));
    }

    // 5. Ticket bearbeiten
    public function edit(Ticket $ticket)
    {
        // Nur Support/Admin oder Besitzer darf bearbeiten
        if (!Auth::user()->hasAnyRole(['support', 'admin']) && $ticket->user_id !== Auth::id()) {
            abort(403, 'Keine Berechtigung!');
        }
        return view('tickets.edit', compact('ticket'));
    }

    // 6. Ticket aktualisieren
    public function update(Request $request, Ticket $ticket)
    {
        if (!Auth::user()->hasAnyRole(['support', 'admin']) && $ticket->user_id !== Auth::id()) {
            abort(403, 'Keine Berechtigung!');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'nullable|string|max:50',
            'priority'    => 'required|in:low,medium,high,critical',
            'reported_at' => 'nullable|date',
            'status'      => 'required|in:open,in_progress,closed',
            'attachment'  => 'nullable|file|max:5120',
        ]);

        // File-Upload behandeln
        if ($request->hasFile('attachment')) {
            if ($ticket->attachment) {
                Storage::disk('public')->delete($ticket->attachment);
            }
            $ticket->attachment = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category'    => $validated['category'] ?? null,
            'priority'    => $validated['priority'],
            'reported_at' => $validated['reported_at'] ?? null,
            'status'      => $validated['status'],
            'attachment'  => $ticket->attachment,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket aktualisiert!');
    }

    // 7. Ticket löschen (nur Admin)
    public function destroy(Ticket $ticket)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Nur Admin darf löschen!');
        }
        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket gelöscht!');
    }
}
