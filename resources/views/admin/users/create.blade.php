@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    <h2>Dodaj Użytkownika</h2>

    {{-- 1. Ogólna lista błędów (PDF str. 15) --}}
    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        
        <label>Login:</label> 
        {{-- Zachowujemy wpisaną wartość przy błędzie używając old() --}}
        <input type="text" name="login" value="{{ old('login') }}">
        {{-- 2. Błąd pod konkretnym polem (PDF str. 16) --}}
        @error('login')
            <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
        @enderror
        <br>

        <label>Hasło:</label> 
        <input type="password" name="password">
        @error('password')
            <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
        @enderror
        <br>

        <label>Imię:</label> 
        <input type="text" name="imie" value="{{ old('imie') }}">
        @error('imie')
            <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
        @enderror
        <br>

        <label>Nazwisko:</label> 
        <input type="text" name="nazwisko" value="{{ old('nazwisko') }}">
        @error('nazwisko')
            <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
        @enderror
        <br>

        <label>Rola:</label>
        <select name="role">
            <option value="uczen" {{ old('role') == 'uczen' ? 'selected' : '' }}>Uczeń</option>
            <option value="nauczyciel" {{ old('role') == 'nauczyciel' ? 'selected' : '' }}>Nauczyciel</option>
            <option value="rodzic" {{ old('role') == 'rodzic' ? 'selected' : '' }}>Rodzic</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role')
            <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
        @enderror
        <br><br>

        <button type="submit">Utwórz konto</button>
    </form>
@endsection
