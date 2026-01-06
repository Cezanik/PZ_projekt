@extends('layout')

@section('content')
<h1>Edycja Klasy</h1>

<form action="{{ route('admin.klasa.update', $klasa->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nazwa Klasy:</label>
    <input type="text" name="nazwa" value="{{ old('nazwa', $klasa->nazwa) }}">
    @error('nazwa')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Wychowawca:</label>
    <select name="wychowawca_id">
        @foreach($nauczyciele as $nauczyciel)
            <option value="{{ $nauczyciel->id }}" 
                {{ old('wychowawca_id', $klasa->wychowawca_id) == $nauczyciel->id ? 'selected' : '' }}>
                {{ $nauczyciel->imie }} {{ $nauczyciel->nazwisko }}
            </option>
        @endforeach
    </select>
    @error('wychowawca_id')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br><br>

    <button type="submit">Zapisz zmiany</button>
</form>
@endsection
