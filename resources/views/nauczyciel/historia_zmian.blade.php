@extends('layout')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">

    <div style="margin-bottom: 20px;">
        <a href="javascript:history.back()" style="text-decoration: none; color: #555; font-size: 14px;">
            &larr; Wróć
        </a>
        <h2 style="margin-top: 10px;">Historia zmian oceny</h2>
        
        <div style="background-color: #f1f3f5; padding: 15px; border-radius: 5px; margin-top: 15px;">
            <strong>Uczeń:</strong> {{ $ocena->uczen->imie }} {{ $ocena->uczen->nazwisko }} <br>
            <strong>Przedmiot:</strong> {{ $ocena->przedmiot->nazwa }} <br>
            <strong>Aktualna ocena:</strong> <span style="font-size: 18px; font-weight: bold; color: #0d6efd;">{{ $ocena->wartosc }}</span>
            <br>
            <strong>Aktualny opis:</strong> {{ $ocena->opis }}
        </div>
    </div>

    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif

    <h3 style="margin-bottom: 10px;">Oś czasu zmian:</h3>

    <ul style="list-style: none; padding: 0;">
        @forelse($historia as $wpis)
            <li style="border-left: 4px solid #ccc; margin-left: 20px; padding-left: 20px; padding-bottom: 20px; position: relative;">
                <div style="position: absolute; left: -10px; top: 0; width: 16px; height: 16px; background: #666; border-radius: 50%;"></div>
                
                <div style="font-size: 13px; color: #666; margin-bottom: 5px;">
                    {{ \Carbon\Carbon::parse($wpis->data_zmiany)->format('d.m.Y H:i:s') }}
                </div>
                
                <div style="background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <strong>Zmiana wartości:</strong> 
                            <span style="color: #dc3545; text-decoration: line-through;">{{ $wpis->stara_wartosc }}</span> 
                            &rarr; 
                            <span style="color: #198754;">{{ $wpis->ocena->wartosc }}</span>
                            <br>
                            <strong>Poprzedni opis:</strong> {{ $wpis->stara_opis ?: '(brak)' }}
                            <br>
                            <strong>Powód zmiany:</strong> <em>{{ $wpis->powod_zmiany }}</em>
                        </div>
                        
                    </div>
                </div>
            </li>
        @empty
            <p style="color: #888; font-style: italic;">Brak historii zmian dla tej oceny.</p>
        @endforelse
    </ul>

</div>
@endsection
