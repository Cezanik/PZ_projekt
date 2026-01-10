@extends('layout')

@section('content')
<div style="padding: 20px; max-width: 600px; margin: 0 auto;">
    <h2>Wystaw ocenę</h2>
    
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <p><strong>Uczeń:</strong> {{ $uczen->imie }} {{ $uczen->nazwisko }}</p>
        <p><strong>Przedmiot:</strong> {{ $przedmiot->nazwa }}</p>
    </div>

    {{-- Błędy walidacji --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  <form action="{{ route('nauczyciel.ocena.store') }}" method="POST" style="border: 1px solid #ccc; padding: 20px; border-radius: 5px;">
        @csrf
        <input type="hidden" name="uczen_id" value="{{ $uczen->id }}">
        <input type="hidden" name="przedmiot_id" value="{{ $przedmiot->id }}">

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Ocena (1-6):</label>
            <input type="number" name="wartosc" step="0.5" min="1" max="6" required style="width: 100%; padding: 8px;" value="{{ old('wartosc') }}">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Opis (np. Kartkówka, Aktywność):</label>
            <input type="text" name="opis" required style="width: 100%; padding: 8px;" value="{{ old('opis') }}">
        </div>

        <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">Zapisz ocenę</button>
        
        {{-- Przycisk Anuluj (powrót do arkusza) --}}
        @php
            $klasaId = $uczen->klasaUcznia()->first()->id ?? 0;
        @endphp
        <a href="{{ route('nauczyciel.arkusz.show', ['klasa' => $klasaId, 'przedmiot' => $przedmiot->id]) }}" style="margin-left: 10px; text-decoration: none; color: #666;">Anuluj</a>
    </form>
</div>
@endsection
