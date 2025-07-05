<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                <p>{{ __("You're logged in!") }}</p>

                <h2 class="mt-6 mb-4 text-lg font-semibold">{{ __('Tickets Übersicht') }}</h2>

                @if($tickets->isEmpty())
                    <p>{{ __('Keine Tickets vorhanden.') }}</p>
                @else
                    <table class="min-w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">{{ __('ID') }}</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">{{ __('Titel') }}</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">{{ __('Beschreibung') }}</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">{{ __('Priorität') }}</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">{{ __('Erstellt am') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $ticket->id }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $ticket->title }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $ticket->description }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
