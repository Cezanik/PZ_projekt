@extends('layout')

@section('content')
<div style="padding: 20px;">
    <h1>Panel Administratora</h1>
    <p>Zalogowany jako: <strong>{{ Auth::user()->imie }} {{ Auth::user()->nazwisko }}</strong></p>
    
    <hr>

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">

        <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
            <h3>👤 Użytkownicy</h3>
            <p>Zarządzanie kontami nauczycieli, uczniów i rodziców.</p>
            <ul>
                <li><a href="{{ route('admin.users.index') }}">Lista wszystkich użytkowników</a></li>
                <li><a href="{{ route('admin.user.create') }}">Dodaj nowego użytkownika</a></li>
            </ul>
        </div>

        <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
            <h3>🏫 Klasy</h3>
            <p>Definiowanie klas i wychowawstw.</p>
            <ul>
                <li><a href="{{ route('admin.klasy.index') }}">Lista klas</a></li>
                <li><a href="{{ route('admin.klasa.create') }}">Utwórz nową klasę</a></li>
            </ul>
        </div>

        <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
            <h3>📚 Przedmioty</h3>
            <p>Baza przedmiotów szkolnych.</p>
            <ul>
                <li><a href="{{ route('admin.przedmioty.index') }}">Lista przedmiotów</a></li>
                <li><a href="{{ route('admin.przedmiot.create') }}">Dodaj przedmiot</a></li>
            </ul>
        </div>

        <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
            <h3>👨‍🏫 Przydziały Zajęć</h3>
            <p>Kto uczy jakiego przedmiotu w jakiej klasie.</p>
            <ul>
                <li><a href="{{ route('admin.przydzialy.index') }}">Tabela przydziałów</a></li>
                <li><a href="{{ route('admin.przydzial.nauczyciel') }}">Nowy przydział (Nauczyciel-Klasa)</a></li>
            </ul>
        </div>

       <div style="border: 1px solid #ccc; padding: 15px; width: 300px;">
            <h3>🎓 Uczniowie i Rodzice</h3>
            <p>Zarządzanie powiązaniami.</p>
            <ul>
                <li><a href="{{ route('admin.uczen.klasa.index') }}">Uczeń-Klasa</a></li>
                <li><a href="{{ route('admin.rodzic.uczen.index') }}">Rodzic-Uczeń</a></li>
                <li><a href="{{ route('admin.uczen.klasa.create') }}">Przypisz ucznia do klasy</a></li>
                <li><a href="{{ route('admin.rodzic.uczen.create') }}">Powiąż rodzica z uczniem</a></li>
            </ul>
        </div>

    </div>

    <hr style="margin-top: 30px;">
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="color: red; cursor: pointer;">Wyloguj się</button>
    </form>
</div>
@endsection
