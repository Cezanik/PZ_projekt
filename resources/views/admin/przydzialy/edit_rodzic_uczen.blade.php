@extends('layout')

@section('content')
<h1>Edytuj Powiązanie: Rodzic - Uczeń</h1>

<div class="mb-4">
    <strong>Rodzic:</strong> {{ $rodzic->imie }} {{ $rodzic->nazwisko }} <br>
    <strong>Obecnie przypisane dziecko:</strong> {{ $uczen->imie }} {{ $uczen->nazwisko }} ({{ $uczen->login }})
</div>

<form action="{{ route('admin.rodzic.uczen.update', [$rodzic->id, $uczen->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="nowy_uczen_id">Zmień na ucznia:</label>
    <select name="nowy_uczen_id" id="nowy_uczen_id" required>
        @foreach($wszyscyUczniowie as $u)
            <option value="{{ $u->id }}" {{ $u->id == $uczen->id ? 'selected' : '' }}>
                {{ $u->imie }} {{ $u->nazwisko }} ({{ $u->login }})
            </option>
        @endforeach
    </select>
    @error('nowy_uczen_id')
        <div style="color: red;">{{ $message }}</div>
    @enderror

    <br><br>
    <button type="submit">Zapisz zmiany</button>
    <a href="{{ route('admin.rodzic.uczen.index') }}">Anuluj</a>
</form>
@endsection
