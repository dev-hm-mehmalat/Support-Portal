@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Ticket Details</h1>

    <div class="bg-white p-6 rounded shadow">
        <p><strong>Titel:</strong> {{ $ticket['title'] }}</p>
        <p><strong>Beschreibung:</strong> {{ $ticket['description'] }}</p>
        <p><strong>Kategorie:</strong> {{ $ticket['category'] ?? 'Keine' }}</p>
        <p><strong>Priorität:</strong> {{ ucfirst($ticket['priority']) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($ticket['status']) }}</p>
        <p><strong>Erstellt am:</strong> {{ $ticket['created_at'] }}</p>

        @if(!empty($ticket['attachment']))
            <p><strong>Anhang:</strong> <a href="{{ asset('storage/' . $ticket['attachment']) }}" target="_blank">Download</a></p>
        @endif

        <a href="{{ route('tickets.index') }}" class="mt-4 inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Zurück zur Übersicht
        </a>
    </div>
@endsection
