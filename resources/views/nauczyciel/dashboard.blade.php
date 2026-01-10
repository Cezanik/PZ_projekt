@extends('layout')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">
    <h2 style="margin-bottom: 5px;">Panel Nauczyciela</h2>
    <p style="color: #666;">Witaj, <strong>{{ Auth::user()->imie }} {{ Auth::user()->nazwisko }}</strong>!</p>

    <hr style="margin: 20px 0;">

    <h3>Twoje narzędzia:</h3>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; background-color: #f8f9fa; width: 300px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #0d6efd;">📋 Lista Przydziałów</h3>
            <p style="font-size: 14px; line-height: 1.5;">
                Przeglądaj klasy i przedmioty, których uczysz. 
                <br><br>
                Kliknij tutaj, aby wybrać klasę i otworzyć jej <strong>Arkusz Ocen</strong>.
            </p>
            <a href="{{ route('nauczyciel.przydzialy.index') }}" style="text-decoration: none;">
                <button style="padding: 10px 15px; cursor: pointer; background-color: #0d6efd; color: white; border: none; border-radius: 5px; width: 100%; font-size: 16px;">
                    Przejdź do przydziałów
                </button>
            </a>
        </div>

        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #fff; width: 300px;">
            <h3 style="margin-top: 0; color: #555;">ℹ️ Jak wystawić ocenę?</h3>
            <ol style="font-size: 14px; padding-left: 20px; color: #444;">
                <li>Kliknij <strong>Przejdź do przydziałów</strong>.</li>
                <li>Znajdź odpowiednią klasę i przedmiot na liście.</li>
                <li>Kliknij przycisk <strong>Arkusz ocen</strong> przy wybranej pozycji.</li>
                <li>W arkuszu możesz dodawać i edytować oceny uczniów.</li>
            </ol>
        </div>
        <div style="border: 1px solid #ccc; padding: 20px; border-radius: 5px; background-color: #f9f9f9; width: 300px;">
    <h4 style="margin-top: 0;">🕘 Historia Aktywności</h4>
    <p>Sprawdź ostatnie zmiany w ocenach i przywróć poprzednie wersje.</p>
    <a href="{{ route('nauczyciel.historia.index') }}">
        <button style="padding: 10px 15px; cursor: pointer; background-color: #6c757d; color: white; border: none; border-radius: 3px; width: 100%;">
            Zobacz historię
        </button>
    </a>
</div>

    </div>
</div>
@endsection
