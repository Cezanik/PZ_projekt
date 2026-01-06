@extends('layout')

@section('content')
<h1>Edytuj Przydział</h1>

<form action="{{ route('admin.przydzial.update', $przydzial->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Klasa:</label>
    <select name="klasa_id" required>
        @foreach($klasy as $klasa)
            <option value="{{ $klasa->id }}" 
                {{ old('klasa_id', $przydzial->klasa_id) == $klasa->id ? 'selected' : '' }}>
                {{ $klasa->nazwa }}
            </option>
        @endforeach
    </select>
    @error('klasa_id') <div style="color:red">{{ $message }}</div> @enderror
    <br><br>

    <label>Przedmiot:</label>
    <select name="przedmiot_id" required>
        @foreach($przedmioty as $przedmiot)
            <option value="{{ $przedmiot->id }}" 
                {{ old('przedmiot_id', $przydzial->przedmiot_id) == $przedmiot->id ? 'selected' : '' }}>
                {{ $przedmiot->nazwa }}
            </option>
        @endforeach
    </select>
    @error('przedmiot_id') <div style="color:red">{{ $message }}</div> @enderror
    <br><br>

    <label>Nauczyciel:</label>
    <select name="nauczyciel_id" required>
        @foreach($nauczyciele as $nauczyciel)
            <option value="{{ $nauczyciel->id }}" 
                {{ old('nauczyciel_id', $przydzial->nauczyciel_id) == $nauczyciel->id ? 'selected' : '' }}>
                {{ $nauczyciel->imie }} {{ $nauczyciel->nazwisko }}
            </option>
        @endforeach
    </select>
    @error('nauczyciel_id') <div style="color:red">{{ $message }}</div> @enderror
    <br><br>

    <button type="submit">Zapisz zmiany</button>
    <a href="{{ route('admin.przydzialy.index') }}">Anuluj</a>
</form>
@endsection
