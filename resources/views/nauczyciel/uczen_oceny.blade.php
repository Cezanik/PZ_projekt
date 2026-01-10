@extends('layout')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">
    
    <div style="margin-bottom: 25px;">
        <a href="{{ route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $przedmiot->id]) }}" 
           style="text-decoration: none; color: #555; font-size: 14px;">
            &larr; Wróć do arkusza klasy
        </a>

        <div style="margin-top: 15px; border-bottom: 2px solid #0d6efd; padding-bottom: 15px;">
            <h2 style="margin: 0; color: #333;">Szczegóły ocen ucznia</h2>
            <div style="margin-top: 10px; font-size: 18px;">
                Uczeń: <strong>{{ $uczen->imie }} {{ $uczen->nazwisko }}</strong> <br>
                Przedmiot: <strong>{{ $przedmiot->nazwa }}</strong>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="box-shadow: 0 0 10px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f8f9fa; color: #495057;">
                <tr>
                    <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Wartość</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Opis</th>
                    <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6;">Data wystawienia</th>
                    <th style="padding: 15px; text-align: right; border-bottom: 2px solid #dee2e6;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($oceny as $ocena)
                    <tr style="border-bottom: 1px solid #eee; background-color: #fff;">
                        
                        <td style="padding: 15px; font-weight: bold; font-size: 18px;">
                            <span style="color: {{ $ocena->wartosc < 2 ? '#dc3545' : ($ocena->wartosc >= 4 ? '#198754' : '#0d6efd') }}">
                                {{ $ocena->wartosc }}
                            </span>
                        </td>

                        <td style="padding: 15px;">
                            {{ $ocena->opis ?: 'Brak opisu' }}
                        </td>

                        <td style="padding: 15px; color: #666;">
                            {{ $ocena->created_at->format('d.m.Y H:i') }}
                        </td>

                        <td style="padding: 15px; text-align: right;">
                            <div style="display: inline-flex; gap: 10px;">
                                <a href="{{ route('nauczyciel.ocena.edit', $ocena->id) }}" 
                                   style="text-decoration: none;">
                                    <button style="background-color: #ffc107; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; color: #000; font-size: 13px;">
                                        Edytuj
                                    </button>
                                </a>

                                <form action="{{ route('ocena.destroy', $ocena->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tę ocenę? TEJ OPERACJI NIE MOŻNA COFNĄĆ.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #dc3545; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; color: white; font-size: 13px;">
                                        Usuń
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #666; font-style: italic;">
                            Ten uczeń nie posiada jeszcze ocen z tego przedmiotu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px; text-align: right;">
        <a href="{{ route('nauczyciel.ocena.create', ['uczen' => $uczen->id, 'przedmiot' => $przedmiot->id]) }}">
            <button style="background-color: #0d6efd; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                + Wystaw nową ocenę
            </button>
        </a>
    </div>

</div>
@endsection
