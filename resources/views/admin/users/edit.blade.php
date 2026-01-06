@extends('layout')

@section('content')
<h1>Edycja Użytkownika</h1>

<form action="{{ route('admin.user.update', $user->id) }}" method="POST">
    {{-- Zabezpieczenie CSRF  --}}
    @csrf
    @method('PUT')

    <label>Imię:</label>
    {{-- Funkcja old() zachowuje dane po błędzie  --}}
    <input type="text" name="imie" value="{{ old('imie', $user->imie) }}">
    {{-- Wyświetlenie błędu walidacji dla pola [cite: 383, 387] --}}
    @error('imie')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Nazwisko:</label>
    <input type="text" name="nazwisko" value="{{ old('nazwisko', $user->nazwisko) }}">
    @error('nazwisko')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Login:</label>
    <input type="text" name="login" value="{{ old('login', $user->login) }}">
    @error('login')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Rola:</label>
    <select name="role">
        <option value="uczen" {{ old('role', $user->role) == 'uczen' ? 'selected' : '' }}>Uczeń</option>
        <option value="nauczyciel" {{ old('role', $user->role) == 'nauczyciel' ? 'selected' : '' }}>Nauczyciel</option>
        <option value="rodzic" {{ old('role', $user->role) == 'rodzic' ? 'selected' : '' }}>Rodzic</option>
        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Hasło (opcjonalnie):</label>
    <input type="password" name="password">
    <br><br>

    <button type="submit">Zapisz zmiany</button>
</form>
@endsection
