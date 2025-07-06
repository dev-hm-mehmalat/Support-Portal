<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    // 1. Alle Tickets (JSON)
    public function index()
    {
        $tickets = Auth::user()->hasAnyRole(['support', 'admin'])
            ? Ticket::latest()->get()
            : Ticket::where('user_id', Auth::id())->latest()->get();

        return response()->json($tickets);
    }

    // 2. Ticket erstellen (JSON)
public function store(Request $request)
{
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'category'    => 'nullable|string|max:50',
        'priority'    => 'required|in:low,medium,high,critical',
    ]);

    $ticket = Ticket::create([
        'title'       => $validated['title'],
        'description' => $validated['description'],
        'category'    => $validated['category'] ?? null,
        'priority'    => $validated['priority'],
        'user_id'     => Auth::id(),
        'status'      => 'open',
    ]);

    // WICHTIG: GANZES Objekt zurückgeben!
    return response()->json($ticket, 201);
}


    // 3. Einzelnes Ticket (JSON)
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->hasAnyRole(['support', 'admin']) && $ticket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($ticket);
    }

    // 4. Ticket updaten (JSON)
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->hasAnyRole(['support', 'admin']) && $ticket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category'    => 'nullable|string|max:50',
            'priority'    => 'sometimes|required|in:low,medium,high,critical',
            'reported_at' => 'nullable|date',
            'status'      => 'sometimes|required|in:open,in_progress,closed',
        ]);

        $ticket->update($validated);

        return response()->json($ticket);
    }

    // 5. Ticket löschen (JSON)
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $ticket->delete();

        return response()->json(null, 204);
    }
}
