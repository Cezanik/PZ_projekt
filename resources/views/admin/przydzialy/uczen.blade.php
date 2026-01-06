@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    
    @if(session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif

    <hr>
    <h2>Przypisz Ucznia do Klasy</h2>
    <form action="/admin/przydzialy/uczen-klasa" method="POST"> 
        @csrf
        <label>Klasa:</label> 
        <select name="klasa_id">
            @foreach($klasy as $klasa)
                <option value="{{ $klasa->id }}">{{ $klasa->nazwa }}</option>
            @endforeach
        </select><br>

        <label>Uczeń:</label>
        <select name="uczniowie_ids[]">
            @foreach($uczniowie as $u)
                <option value="{{ $u->id }}">{{ $u->imie }} {{ $u->nazwisko }} ({{ $u->login }})</option>
            @endforeach
        </select>
        <br>
        <button type="submit">Dodaj ucznia do klasy</button>
    </form>

    <hr>
    <h2>Powiąż Rodzica z Dzieckiem</h2>
    <form action="/admin/przydzialy/rodzic-uczen" method="POST"> 
        @csrf
        <label>Rodzic:</label>
        <select name="rodzic_id">
             @foreach($rodzice as $r)
                <option value="{{ $r->id }}">{{ $r->imie }} {{ $r->nazwisko }}</option>
            @endforeach
        </select><br>

        <label>Dziecko (Uczeń):</label> 
        <select name="uczen_id">
             @foreach($uczniowie as $u)
                <option value="{{ $u->id }}">{{ $u->imie }} {{ $u->nazwisko }}</option>
            @endforeach
        </select><br><br>
        
        <button type="submit">Powiąż</button>
    </form>
@endsection
