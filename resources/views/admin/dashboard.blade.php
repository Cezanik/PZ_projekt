@extends('layout')

@section('content')
<div class="container" style="padding: 20px;">
    <h1 style="margin-bottom: 20px;">Panel Administratora</h1>
    <p>Witaj w panelu zarządzania. Wybierz moduł, aby przejść do formularzy:</p>
    
    <hr>

    {{-- Kontener na kafelki (Grid) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="color: #2c3e50;">👤 Użytkownicy</h3>
            <p>Dodawanie nowych kont dla uczniów, nauczycieli i rodziców.</p>
            <a href="{{ route('admin.user.create') }}" class="btn" style="text-decoration: none; color: white; background-color: #007bff; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                Dodaj Użytkownika &rarr;
            </a>
        </div>

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="color: #2c3e50;">🏫 Struktura Szkoły</h3>
            <p>Tworzenie klas, przedmiotów i zarządzanie wychowawstwami.</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.klasa.create') }}" style="text-decoration: none; color: #007bff; font-weight: bold;">
                    + Utwórz Klasę
                </a>
                <span style="color: #ccc;">|</span>
                <a href="{{ route('admin.przedmiot.create') }}" style="text-decoration: none; color: #007bff; font-weight: bold;">
                    + Dodaj Przedmiot
                </a>
            </div>
        </div>

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="color: #2c3e50;">📚 Przydziały Zajęć</h3>
            <p>Łączenie: Nauczyciel ↔ Przedmiot ↔ Klasa.</p>
            <a href="{{ route('admin.przydzial.nauczyciel') }}" class="btn" style="text-decoration: none; color: white; background-color: #28a745; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                Zarządzaj Przydziałami &rarr;
            </a>
        </div>

        <div class="card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="color: #2c3e50;">🎓 Uczniowie i Rodzice</h3>
            <p>Przypisywanie uczniów do klas oraz łączenie ich z rodzicami.</p>
            <a href="{{ route('admin.uczen.przydzial') }}" class="btn" style="text-decoration: none; color: white; background-color: #17a2b8; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                Zarządzaj Uczniami &rarr;
            </a>
        </div>

    </div>

    <hr style="margin-top: 30px;">
    
    <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
        @csrf
        <button type="submit" style="background: none; border: none; color: red; cursor: pointer; text-decoration: underline;">
            Wyloguj się z systemu
        </button>
    </form>
</div>
@endsection
