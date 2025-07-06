<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller 


{
    public function __construct()
    {
        // Alle Methoden erfordern Login
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

    // Ticket speichern (inkl. Validierung und Speicherung im Cache)
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

        $tickets = Cache::get('tickets', []);
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $id = count($tickets) ? max(array_column($tickets, 'id')) + 1 : 1;

        $newTicket = [
            'id'          => $id,
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category'    => $validated['category'] ?? null,
            'priority'    => $validated['priority'],
            'user_id'     => Auth::id(),
            'status'      => 'open',
            'reported_at' => $validated['reported_at'] ?? null,
            'attachment'  => $attachmentPath,
            'created_at'  => now()->toDateTimeString(),
        ];

        $tickets[] = $newTicket;
        Cache::put('tickets', $tickets);

        return redirect()->route('tickets.index')->with('success', 'Ticket erfolgreich erstellt!');
    }

    // Einzelnes Ticket anzeigen
    public function show($id)
    {
        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', (int)$id);

        if (!$ticket) {
            abort(404);
        }

        return view('tickets.show', compact('ticket'));
    }

    // Formular zum Bearbeiten anzeigen (nur Support/Admin)
    public function edit($id)
    {
        if (!Auth::user()->hasAnyRole(['support', 'admin'])) {
            abort(403, 'Keine Berechtigung!');
        }

        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', (int)$id);

        if (!$ticket) {
            abort(404);
        }

        return view('tickets.edit', compact('ticket'));
    }

    // Ticket aktualisieren (nur Support/Admin)
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['support', 'admin'])) {
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

        $tickets = Cache::get('tickets', []);
        $index = collect($tickets)->search(fn($t) => $t['id'] == (int)$id);

        if ($index === false) {
            abort(404);
        }

        $attachmentPath = $tickets[$index]['attachment'] ?? null;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $tickets[$index] = array_merge($tickets[$index], [
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'category'    => $validated['category'] ?? null,
            'priority'    => $validated['priority'],
            'status'      => $validated['status'],
            'reported_at' => $validated['reported_at'] ?? null,
            'attachment'  => $attachmentPath,
        ]);

        Cache::put('tickets', $tickets);

        // Notification an Ticketbesitzer senden
        $ticketOwner = User::find($tickets[$index]['user_id']);
        if ($ticketOwner) {
            $ticketOwner->notify(new TicketStatusChanged($tickets[$index]));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket aktualisiert!');
    }

    // Ticket löschen (nur Admin)
    public function destroy($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Nur Admin darf löschen!');
        }

        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', (int)$id);

        if ($ticket && !empty($ticket['attachment'])) {
            Storage::disk('public')->delete($ticket['attachment']);
        }

        $tickets = collect($tickets)->reject(fn($t) => $t['id'] == (int)$id)->values()->all();
        Cache::put('tickets', $tickets);

        return redirect()->route('tickets.index')->with('success', 'Ticket gelöscht!');
    }
}
