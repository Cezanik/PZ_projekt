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
    <br><br>

    <button type="submit">Zapisz zmiany</button>
</form>
@endsection
