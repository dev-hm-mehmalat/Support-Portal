<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        {{-- Erfolgsmeldung --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-200 dark:bg-green-700 text-green-900 dark:text-green-100 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('tickets.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-700 transition">
                + Neues Ticket erstellen
            </a>
        </div>

@if(count($tickets))            <div class="overflow-x-auto rounded shadow bg-white dark:bg-gray-800">
                <table class="min-w-full table-auto text-sm">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Titel</th>
                            <th class="px-4 py-2 text-left">Kategorie</th>
                            <th class="px-4 py-2 text-left">Priorität</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Erstmeldung</th>
                            <th class="px-4 py-2 text-left">Ersteller</th>
                            <th class="px-4 py-2 text-left">Anhang</th>
                            <th class="px-4 py-2 text-left">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2">{{ $ticket->id }}</td>
                                <td class="px-4 py-2">
                                    <span class="font-bold">{{ $ticket->title }}</span><br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->description }}</span>
                                </td>
                                <td class="px-4 py-2">{{ $ticket->category ?? '-' }}</td>
                                <td class="px-4 py-2 capitalize">{{ $ticket->priority ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700">
                                        {{ __($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    {{ $ticket->reported_at ? \Carbon\Carbon::parse($ticket->reported_at)->format('d.m.Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-2">{{ $ticket->user->name ?? 'Unbekannt' }}</td>
                                <td class="px-4 py-2">
                                    @if($ticket->attachment)
                                        <a href="{{ asset('storage/'.$ticket->attachment) }}"
                                           target="_blank"
                                           class="underline text-blue-600 dark:text-blue-400">Anhang</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-1">
                                    <a href="{{ route('tickets.show', $ticket) }}"
                                       class="text-blue-600 dark:text-blue-300 hover:underline">Anzeigen</a>
                                    <a href="{{ route('tickets.edit', $ticket) }}"
                                       class="text-yellow-600 dark:text-yellow-300 hover:underline">Bearbeiten</a>
                                    @can('delete', $ticket)
                                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline"
                                                    onclick="return confirm('Ticket wirklich löschen?')">
                                                Löschen
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-gray-600 dark:text-gray-300 mt-10">
                <span>Keine Tickets vorhanden.</span>
            </div>
        @endif
    </div>
</x-app-layout>


