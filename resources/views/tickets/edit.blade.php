<div>
    <label for="status" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">
        Status <span class="text-red-500">*</span>
    </label>
    <select name="status" id="status" required
        class="w-full rounded border border-gray-300 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Offen</option>
        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Bearbeitung</option>
        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Geschlossen</option>
    </select>
</div>
