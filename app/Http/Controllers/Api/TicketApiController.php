<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketStatusUpdated; // Falls du Events nutzt

class TicketApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Alle Tickets als JSON zurückgeben
     */
    public function index()
    {
        $tickets = Cache::get('tickets', []);
        return response()->json($tickets);
    }

    /**
     * Neues Ticket anlegen
     */
    public function store(Request $request)
    {
        // Validierung
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $tickets = Cache::get('tickets', []);
        $newId = count($tickets) > 0 ? max(array_column($tickets, 'id')) + 1 : 1;

        $newTicket = array_merge($data, [
            'id' => $newId,
            'user_id' => Auth::id(),
            'status' => 'open',
            'created_at' => now()->toDateTimeString(),
        ]);

        $tickets[] = $newTicket;
        Cache::put('tickets', $tickets);

        return response()->json($newTicket, 201);
    }

    /**
     * Einzelnes Ticket anzeigen
     */
    public function show($id)
    {
        $tickets = Cache::get('tickets', []);
        $ticket = collect($tickets)->firstWhere('id', (int) $id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket nicht gefunden'], 404);
        }

        return response()->json($ticket);
    }

    /**
     * Ticket aktualisieren
     */
    public function update(Request $request, $id)
    {
        $tickets = Cache::get('tickets', []);
        $index = collect($tickets)->search(fn($t) => $t['id'] == (int)$id);

        if ($index === false) {
            return response()->json(['message' => 'Ticket nicht gefunden'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'nullable|string|max:50',
            'priority' => 'sometimes|required|in:low,medium,high,critical',
            'status' => 'sometimes|required|in:open,in_progress,closed',
        ]);

        // Merge und Cache updaten
        $tickets[$index] = array_merge($tickets[$index], $data);
        Cache::put('tickets', $tickets);

        // Event feuern, wenn Status sich ändert (optional)
        if (isset($data['status'])) {
            event(new TicketStatusUpdated($tickets[$index]));
        }

        return response()->json($tickets[$index]);
    }

    /**
     * Ticket löschen
     */
    public function destroy($id)
    {
        $tickets = Cache::get('tickets', []);
        $countBefore = count($tickets);

        $tickets = collect($tickets)->reject(fn($t) => $t['id'] == (int)$id)->values()->all();

        if (count($tickets) === $countBefore) {
            return response()->json(['message' => 'Ticket nicht gefunden'], 404);
        }

        Cache::put('tickets', $tickets);

        return response()->json(null, 204);
    }
}
