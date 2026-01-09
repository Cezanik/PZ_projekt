@extends('layout')

@section('content')
<div style="padding: 20px;">
    <h2>Panel Nauczyciela</h2>
    <p>Witaj, <strong>{{ Auth::user()->imie }} {{ Auth::user()->nazwisko }}</strong>!</p>

    <hr style="margin: 20px 0;">

    <h3>Dostępne akcje:</h3>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 5px; background-color: #f9f9f9; width: 300px;">
            <h4 style="margin-top: 0;">📂 Dziennik Lekcyjny</h4>
            <p>Przeglądaj swoje klasy, przedmioty i wystawiaj oceny.</p>
            <a href="{{ route('nauczyciel.przydzialy.index') }}">
                <button style="padding: 10px 15px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 3px; width: 100%;">
                    Przejdź do klas
                </button>
            </a>
        </div>

        </div>

    <hr style="margin: 30px 0;">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background-color: transparent; border: 1px solid red; color: red; padding: 5px 10px; cursor: pointer;">
            Wyloguj się
        </button>
    </form>
</div>
@endsection
