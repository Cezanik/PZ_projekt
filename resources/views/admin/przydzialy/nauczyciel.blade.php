@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    <h2>Przydział: Nauczyciel -> Przedmiot -> Klasa</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="/admin/przydzialy/nauczyciel" method="POST">
        @csrf
        <label>Klasa:</label> 
        <select name="klasa_id">
            @foreach($klasy as $klasa)
                <option value="{{ $klasa->id }}">{{ $klasa->nazwa }}</option>
            @endforeach
        </select><br>

        <label>Przedmiot:</label> 
        <select name="przedmiot_id">
            @foreach($przedmioty as $przedmiot)
                <option value="{{ $przedmiot->id }}">{{ $przedmiot->nazwa }}</option>
            @endforeach
        </select><br>

        <label>Nauczyciel:</label> 
        <select name="nauczyciel_id">
            @foreach($nauczyciele as $n)
                <option value="{{ $n->id }}">{{ $n->imie }} {{ $n->nazwisko }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Przypisz</button>
    </form>
@endsection
