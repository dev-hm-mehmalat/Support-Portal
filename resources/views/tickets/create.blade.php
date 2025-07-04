@extends('layouts.app')

@section('content')
    <h1>Neues Ticket erstellen</h1>

    {{-- Fehleranzeige --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Erfolgsmeldung --}}
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="title">Titel *</label>
        <input type="text" name="title" id="title" required value="{{ old('title') }}">
        <br>

        <label for="description">Beschreibung *</label>
        <textarea name="description" id="description" required>{{ old('description') }}</textarea>
        <br>

        <label for="category">Kategorie</label>
        <select name="category" id="category">
            <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
            <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software</option>
            <option value="Netzwerk" {{ old('category') == 'Netzwerk' ? 'selected' : '' }}>Netzwerk</option>
            <option value="Sonstiges" {{ old('category') == 'Sonstiges' ? 'selected' : '' }}>Sonstiges</option>
        </select>
        <br>

        <label for="priority">Priorität</label>
        <select name="priority" id="priority">
            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Niedrig</option>
            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Mittel</option>
            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Hoch</option>
            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Kritisch</option>
        </select>
        <br>

        <label for="reported_at">Problemerstmeldung (Zeit)</label>
        <input type="datetime-local" name="reported_at" id="reported_at" value="{{ old('reported_at') }}">
        <br>

        <label for="attachment">Anhang (optional)</label>
        <input type="file" name="attachment" id="attachment">
        <br>

        <button type="submit">Ticket absenden</button>
    </form>
@endsection
