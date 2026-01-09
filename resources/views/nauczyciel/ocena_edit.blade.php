@extends('layout')

@section('content')
<div style="padding: 20px; max-width: 600px; margin: 0 auto;">
    <h2 style="color: #e67e22;">Edytuj ocenę</h2>

    <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeeba;">
        <p><strong>Uczeń:</strong> {{ $ocena->uczen->imie }} {{ $ocena->uczen->nazwisko }}</p>
        <p><strong>Przedmiot:</strong> {{ $ocena->przedmiot->nazwa }}</p>
        <p><strong>Data wystawienia:</strong> {{ $ocena->data_wystawienia }}</p>
    </div>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ocena.update', $ocena->id) }}" method="POST" style="border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Ocena:</label>
            <input type="number" name="wartosc" step="0.5" min="1" max="6" required style="width: 100%; padding: 8px;" value="{{ old('wartosc', $ocena->wartosc) }}">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Opis:</label>
            <input type="text" name="opis" required style="width: 100%; padding: 8px;" value="{{ old('opis', $ocena->opis) }}">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #d35400; font-weight: bold;">Powód zmiany (wymagane):</label>
            <textarea name="powod_zmiany" required rows="3" style="width: 100%; padding: 8px;" placeholder="Wyjaśnij dlaczego zmieniasz ocenę...">{{ old('powod_zmiany') }}</textarea>
        </div>

        <button type="submit" style="background-color: #e67e22; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">Zaktualizuj ocenę</button>
        
        @php
            $klasaId = $ocena->uczen->klasaUcznia()->first()->id ?? 0;
        @endphp
        <a href="{{ route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $ocena->przedmiot_id]) }}" style="margin-left: 10px; text-decoration: none; color: #666;">Anuluj</a>
    </form>
</div>
@endsection
