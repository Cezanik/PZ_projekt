@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    
    @if(session('success'))
        <div style="color: green; margin: 10px 0; font-weight: bold;">{{ session('success') }}</div>
    @endif

    <h2>Przypisz Ucznia do Klasy</h2>
    
    <form action="{{ route('admin.uczen.przypisz') }}" method="POST"> 
        @csrf
        
        <label>Wybierz Klasę:</label><br>
        <select name="klasa_id">
            <option value="">-- Wybierz --</option>
            @foreach($klasy as $klasa)
                <option value="{{ $klasa->id }}" {{ old('klasa_id') == $klasa->id ? 'selected' : '' }}>
                    {{ $klasa->nazwa }}
                </option>
            @endforeach
        </select>
        @error('klasa_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <label>Wybierz Uczniów (przytrzymaj Ctrl dla wielu):</label><br>
        <select name="uczniowie_ids[]" multiple size="10" style="width: 300px;">
            @foreach($uczniowie as $u)
                <option value="{{ $u->id }}" 
                    {{ (collect(old('uczniowie_ids'))->contains($u->id)) ? 'selected' : '' }}>
                    {{ $u->imie }} {{ $u->nazwisko }} ({{ $u->login }})
                </option>
            @endforeach
        </select>
        @error('uczniowie_ids') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <button type="submit">Zapisz przypisanie</button>
    </form>
@endsection
