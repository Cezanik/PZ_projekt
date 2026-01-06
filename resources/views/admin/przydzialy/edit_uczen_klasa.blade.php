@extends('layout')

@section('content')
<h1>Edytuj Przydział: Uczeń - Klasa</h1>

<div class="mb-4">
    <strong>Uczeń:</strong> {{ $uczen->imie }} {{ $uczen->nazwisko }} ({{ $uczen->login }}) <br>
    <strong>Obecna Klasa:</strong> {{ $klasa->nazwa }}
</div>

<form action="{{ route('admin.uczen.klasa.update', [$uczen->id, $klasa->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="nowa_klasa_id">Wybierz nową klasę:</label>
    <select name="nowa_klasa_id" id="nowa_klasa_id" required>
        @foreach($dostepneKlasy as $k)
            <option value="{{ $k->id }}" {{ $k->id == $klasa->id ? 'selected' : '' }}>
                {{ $k->nazwa }}
            </option>
        @endforeach
    </select>
    @error('nowa_klasa_id')
        <div style="color: red;">{{ $message }}</div>
    @enderror

    <br><br>
    <button type="submit">Zapisz zmiany</button>
    <a href="{{ route('admin.uczen.klasa.index') }}">Anuluj</a>
</form>
@endsection
