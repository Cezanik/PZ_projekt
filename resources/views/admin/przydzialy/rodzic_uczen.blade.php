@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>

    @if(session('success'))
        <div style="color: green; margin: 10px 0; font-weight: bold;">{{ session('success') }}</div>
    @endif

    <h2>Powiąż Rodzica z Dzieckiem</h2>
    
    <form action="{{ route('admin.rodzic.powiaz') }}" method="POST"> 
        @csrf
        
        <label>Wybierz Rodzica:</label><br>
        <select name="rodzic_id">
            <option value="">-- Wybierz --</option>
            @foreach($rodzice as $r)
                <option value="{{ $r->id }}" {{ old('rodzic_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->imie }} {{ $r->nazwisko }} ({{ $r->login }})
                </option>
            @endforeach
        </select>
        @error('rodzic_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>

        <label>Wybierz Dziecko (Ucznia):</label><br>
        <select name="uczen_id">
            <option value="">-- Wybierz --</option>
            @foreach($uczniowie as $u)
                <option value="{{ $u->id }}" {{ old('uczen_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->imie }} {{ $u->nazwisko }} ({{ $u->login }})
                </option>
            @endforeach
        </select>
        @error('uczen_id') <div style="color: red">{{ $message }}</div> @enderror
        <br><br>
        
        <button type="submit">Powiąż konta</button>
    </form>
@endsection
