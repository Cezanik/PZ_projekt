@extends('layout')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">
    
    <div style="margin-bottom: 25px;">
        <a href="{{ route('nauczyciel.przydzialy.index') }}" style="text-decoration: none; color: #555; font-size: 14px;">
            &larr; Wróć do listy przydziałów
        </a>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <h2 style="margin: 0;">Arkusz Ocen</h2>
        </div>

        <div style="background-color: #f8f9fa; border-left: 5px solid #0d6efd; padding: 15px; margin-top: 15px; border-radius: 4px;">
            <h3 style="margin: 0 0 5px 0; color: #333;">{{ $klasa->nazwa }}</h3>
            <span style="color: #666;">Przedmiot: <strong>{{ $przedmiot->nazwa }}</strong></span>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="box-shadow: 0 0 10px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f1f3f5; color: #495057;">
                <tr>
                    <th style="padding: 15px; text-align: left; width: 25%; border-bottom: 2px solid #dee2e6;">Uczeń</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Oceny (Kliknij, aby edytować)</th>
                    <th style="padding: 15px; text-align: right; width: 150px; border-bottom: 2px solid #dee2e6;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uczniowie as $uczen)
                    <tr style="border-bottom: 1px solid #eee;">
                        
                        <td style="padding: 15px; vertical-align: middle;">
    <div style="font-weight: bold; font-size: 16px;">
        <a href="{{ route('nauczyciel.oceny.uczen.details', ['uczen' => $uczen->id, 'przedmiot' => $przedmiot->id]) }}" 
           style="text-decoration: none; color: #0d6efd;">
            {{ $uczen->imie }} {{ $uczen->nazwisko }}
        </a>
    </div>

</td>

                        <td style="padding: 15px; vertical-align: middle;">
                            @forelse($uczen->ocenyOtrzymane as $ocena)
                                <a href="{{ route('nauczyciel.ocena.edit', $ocena->id) }}" 
                                   title="Opis: {{ $ocena->opis ?? 'Brak' }} | Data: {{ $ocena->created_at->format('Y-m-d') }}"
                                   style="text-decoration: none;">
                                    <span style="
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        width: 35px;
                                        height: 35px;
                                        background-color: #fff;
                                        border: 2px solid {{ $ocena->wartosc < 2 ? '#dc3545' : ($ocena->wartosc >= 4 ? '#198754' : '#ffc107') }};
                                        color: #333;
                                        border-radius: 50%;
                                        margin-right: 5px;
                                        font-weight: bold;
                                        font-size: 14px;
                                        transition: 0.2s;
                                    " onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='#fff'">
                                        {{ $ocena->wartosc }}
                                    </span>
                                </a>
                            @empty
                                <span style="color: #ccc; font-style: italic;">Brak ocen</span>
                            @endforelse
                        </td>

                        <td style="padding: 15px; text-align: right; vertical-align: middle;">
                            <a href="{{ route('nauczyciel.ocena.create', ['uczen' => $uczen->id, 'przedmiot' => $przedmiot->id]) }}">
                                <button style="
                                    background-color: #0d6efd; 
                                    color: white; 
                                    border: none; 
                                    padding: 8px 12px; 
                                    border-radius: 4px; 
                                    cursor: pointer;
                                    font-size: 13px;">
                                    + Dodaj ocenę
                                </button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 20px; text-align: center; color: #666;">
                            Brak uczniów przypisanych do tej klasy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
