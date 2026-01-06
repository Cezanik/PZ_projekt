@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    
    {{-- Ogólny komunikat sukcesu --}}
    @if(session('success'))
        <div style="color: green; margin: 10px 0; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Ogólna lista błędów (opcjonalnie, jeśli wolisz listę na górze) --}}
    @if($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin: 10px 0;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr>
    
    <h2>Przypisz Ucznia do Klasy</h2>
    
    {{-- Zmiana: Używamy route() zamiast sztywnego URL --}}
    <form action="{{ route('admin.uczen.przypisz') }}" method="POST"> 
        @csrf
        
        <label>Klasa:</label> <br>
        <select name="klasa_id">
            <option value="">-- Wybierz klasę --</option>
            @foreach($klasy as $klasa)
                {{-- Zmiana: old() zapamiętuje wybór po błędzie --}}
                <option value="{{ $klasa->id }}" {{ old('klasa_id') == $klasa->id ? 'selected' : '' }}>
                    {{ $klasa->nazwa }}
                </option>
            @endforeach
        </select>
        {{-- Zmiana: Wyświetlanie błędu pod polem --}}
        @error('klasa_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <label>Uczniowie (przytrzymaj Ctrl, aby wybrać wielu):</label><br>
        {{-- Zmiana: Dodano 'multiple', aby można było wybrać kilku uczniów naraz --}}
        <select name="uczniowie_ids[]" multiple size="8" style="width: 300px;">
            @foreach($uczniowie as $u)
                {{-- Logika sprawdzania old() dla tablicy (in_array) --}}
                <option value="{{ $u->id }}" 
                    {{ (collect(old('uczniowie_ids'))->contains($u->id)) ? 'selected' : '' }}>
                    {{ $u->imie }} {{ $u->nazwisko }} ({{ $u->login }})
                </option>
            @endforeach
        </select>
        @error('uczniowie_ids') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <button type="submit">Dodaj uczniów do klasy</button>
    </form>

    <hr>

    <h2>Powiąż Rodzica z Dzieckiem</h2>
    
    <form action="{{ route('admin.rodzic.powiaz') }}" method="POST"> 
        @csrf
        
        <label>Rodzic:</label><br>
        <select name="rodzic_id">
            <option value="">-- Wybierz rodzica --</option>
            @foreach($rodzice as $r)
                <option value="{{ $r->id }}" {{ old('rodzic_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->imie }} {{ $r->nazwisko }}
                </option>
            @endforeach
        </select>
        @error('rodzic_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <label>Dziecko (Uczeń):</label><br>
        <select name="uczen_id">
            <option value="">-- Wybierz ucznia --</option>
            @foreach($uczniowie as $u)
                <option value="{{ $u->id }}" {{ old('uczen_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->imie }} {{ $u->nazwisko }}
                </option>
            @endforeach
        </select>
        @error('uczen_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>
        
        <button type="submit">Powiąż</button>
    </form>
@endsection
