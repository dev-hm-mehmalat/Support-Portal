<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ticket anzeigen') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4 text-blue-700 dark:text-blue-300">{{ $ticket->title }}</h1>

        <div class="mb-2">
            <strong>Kategorie:</strong> {{ $ticket->category ?? '-' }}
        </div>
        <div class="mb-2">
            <strong>Status:</strong>
            <span class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700">
                {{ __($ticket->status) }}
            </span>
        </div>
        <div class="mb-2">
            <strong>Priorität:</strong>
            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-900">
                {{ ucfirst($ticket->priority) }}
            </span>
        </div>
        <div class="mb-2">
            <strong>Erstellt am:</strong>
            {{ $ticket->created_at instanceof \Carbon\Carbon ? $ticket->created_at->format('d.m.Y H:i') : $ticket->created_at }}
        </div>
        <div class="mb-2">
            <strong>Ersteller:</strong> {{ $ticket->user->name ?? 'Unbekannt' }}
        </div>
        @if($ticket->reported_at)
            <div class="mb-2">
                <strong>Problemerstmeldung:</strong>
                {{ $ticket->reported_at instanceof \Carbon\Carbon ? $ticket->reported_at->format('d.m.Y H:i') : $ticket->reported_at }}
            </div>
        @endif
        <div class="mb-4">
            <strong>Beschreibung:</strong>
            <div class="bg-gray-100 dark:bg-gray-900 p-2 rounded mt-1 text-gray-800 dark:text-gray-200">
                {{ $ticket->description }}
            </div>
        </div>
        @if($ticket->attachment)
            <div class="mb-4">
                <strong>Anhang:</strong>
                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                   class="text-blue-700 dark:text-blue-300 underline hover:text-blue-900 dark:hover:text-blue-500">
                   Datei anzeigen / herunterladen
                </a>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <a href="{{ route('tickets.edit', $ticket) }}"
               class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                Bearbeiten
            </a>
            <a href="{{ route('tickets.index') }}"
               class="inline-block text-blue-600 dark:text-blue-300 hover:underline">
                Zurück zur Übersicht
            </a>
        </div>
    </div>
</x-app-layout>
