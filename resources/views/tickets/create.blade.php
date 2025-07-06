<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Neues Ticket erstellen') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <h1 class="text-2xl mb-4 font-bold text-blue-600 dark:text-blue-400">Neues Ticket erstellen</h1>

        {{-- Fehler anzeigen --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded shadow">
            @csrf

            {{-- Titel --}}
            <div>
                <label for="title" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Titel <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Beschreibung --}}
            <div>
                <label for="description" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Beschreibung <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="5" required
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            {{-- Kategorie --}}
            <div>
                <label for="category" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Kategorie
                </label>
                <select name="category" id="category"
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Bitte wählen</option>
                    <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                    <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software</option>
                    <option value="Netzwerk" {{ old('category') == 'Netzwerk' ? 'selected' : '' }}>Netzwerk</option>
                    <option value="Sonstiges" {{ old('category') == 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
                </select>
            </div>

            {{-- Priorität --}}
            <div>
                <label for="priority" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Priorität <span class="text-red-500">*</span>
                </label>
                <select name="priority" id="priority" required
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled {{ old('priority') ? '' : 'selected' }}>Bitte wählen</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Niedrig</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Mittel</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Hoch</option>
                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Kritisch</option>
                </select>
            </div>

            {{-- Problemerstmeldung --}}
            <div>
                <label for="reported_at" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Problemerstmeldung (Datum & Zeit)
                </label>
                <input type="datetime-local" name="reported_at" id="reported_at" value="{{ old('reported_at') }}"
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Anhang --}}
            <div>
                <label for="attachment" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
                    Anhang (optional)
                </label>
                <input type="file" name="attachment" id="attachment"
                    class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                Ticket absenden
            </button>
        </form>
    </div>
</x-app-layout>
