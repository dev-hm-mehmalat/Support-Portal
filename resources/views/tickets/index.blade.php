@extends('layouts.app')

@section('content')
    <h1>Tickets (Cache-basiert)</h1>

    @if (session('success'))
        <div style="background: #c9ffc9; border: 1px solid #38a169; padding: 10px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('tickets.create') }}" style="display:inline-block; margin-bottom: 15px;">Neues Ticket erstellen</a>

    @if (count($tickets) === 0)
        <p>Keine Tickets vorhanden.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fafafa;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titel</th>
                    <th>Kategorie</th>
                    <th>Priorität</th>
                    <th>Status</th>
                    <th>Erstmeldung</th>
                    <th>Anhang</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket['id'] }}</td>
                        <td>
                            <strong>{{ $ticket['title'] }}</strong><br>
                            <small>{{ $ticket['description'] }}</small>
                        </td>
                        <td>{{ $ticket['category'] ?? '-' }}</td>
                        <td>{{ ucfirst($ticket['priority'] ?? '-') }}</td>
                        <td>{{ ucfirst($ticket['status']) }}</td>
                        <td>{{ $ticket['reported_at'] ?? '-' }}</td>
                        <td>
                            @if(!empty($ticket['attachment']))
                                <a href="{{ asset('storage/'.$ticket['attachment']) }}" target="_blank">Anhang ansehen</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket['id']) }}">Anzeigen</a> |
                            <a href="{{ route('tickets.edit', $ticket['id']) }}">Bearbeiten</a>
                            <form action="{{ route('tickets.destroy', $ticket['id']) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Ticket wirklich löschen?');">Löschen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
