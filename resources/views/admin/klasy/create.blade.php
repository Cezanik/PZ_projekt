@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    <h2>Utwórz Klasę</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="/admin/klasy" method="POST">
        @csrf
        <label>Nazwa Klasy (np. 1A):</label> <input type="text" name="nazwa" required><br>
        
        <label>Wychowawca:</label>
        <select name="wychowawca_id">
            <option value="">-- Wybierz nauczyciela --</option>
            @foreach($nauczyciele as $nauczyciel)
                <option value="{{ $nauczyciel->id }}">{{ $nauczyciel->imie }} {{ $nauczyciel->nazwisko }}</option>
            @endforeach
        </select>
        <br><br>
        <button type="submit">Utwórz klasę</button>
    </form>
@endsection
