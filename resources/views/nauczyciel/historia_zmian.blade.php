@extends('layout')

@section('content')
<div style="padding: 20px;">
    <h2>Historia zmian oceny</h2>
    
    {{-- Powrót dynamiczny do arkusza --}}
    <a href="{{ route('nauczyciel.arkusz.show', ['klasa' => $ocena->uczen->klasaUcznia->first()->id ?? 0, 'przedmiot' => $ocena->przedmiot_id]) }}">
        ← Wróć do arkusza ocen
    </a>

    <div style="margin-top: 20px; border: 1px solid #ccc; padding: 15px; background-color: #f9f9f9;">
        <h3>Szczegóły oceny (Aktualny stan)</h3>
        <p><strong>Uczeń:</strong> {{ $ocena->uczen->imie }} {{ $ocena->uczen->nazwisko }}</p>
        <p><strong>Przedmiot:</strong> {{ $ocena->przedmiot->nazwa ?? 'Brak nazwy' }}</p>
        <p><strong>Wartość:</strong> {{ $ocena->wartosc }}</p>
        <p><strong>Opis:</strong> {{ $ocena->opis }}</p>
        <p><strong>Data wystawienia:</strong> {{ $ocena->data_wystawienia }}</p>
    </div>

    <h3>Rejestr zmian</h3>
    <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #eee;">
                <th>Data zmiany</th>
                <th>Poprzednia wartość</th>
                <th>Poprzedni opis</th>
                <th>Powód zmiany</th>
                <th>ID Edytującego</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historia as $wpis)
                <tr>
                    <td>{{ $wpis->data_zmiany }}</td>
                    <td>{{ $wpis->stara_wartosc }}</td>
                    <td>{{ $wpis->stara_opis }}</td>
                    <td style="font-style: italic;">{{ $wpis->powod_zmiany }}</td>
                    <td>{{ $wpis->zmienil_user_id }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Brak historii zmian dla tej oceny.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
