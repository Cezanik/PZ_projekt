@extends('layout')

@section('content')
<h1>Edycja Przedmiotu</h1>

<form action="{{ route('admin.przedmiot.update', $przedmiot->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nazwa Przedmiotu:</label>
    <input type="text" name="nazwa" value="{{ old('nazwa', $przedmiot->nazwa) }}">
    @error('nazwa')
        <div style="color: red;">{{ $message }}</div>
    @enderror
    <br><br>

    <button type="submit">Zapisz zmiany</button>
</form>
@endsection
