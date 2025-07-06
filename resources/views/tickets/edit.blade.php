{{-- resources/views/tickets/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ticket bearbeiten
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <h1 class="text-2xl mb-4 font-bold text-blue-700 dark:text-blue-300">Ticket bearbeiten</h1>
        <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
            @csrf
            @method('PUT')

            {{-- Titel --}}
            <div>
                <label for="title" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Titel *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $ticket->title) }}" required class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Beschreibung --}}
            <div>
                <label for="description" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Beschreibung *</label>
                <textarea name="description" id="description" rows="5" required class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $ticket->description) }}</textarea>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Offen</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Bearbeitung</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Geschlossen</option>
                </select>
            </div>

            {{-- ... weitere Felder wie Kategorie, Anhang usw ... --}}

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Speichern
            </button>
        </form>
    </div>
</x-app-layout>