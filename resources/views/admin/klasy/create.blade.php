@extends('layout')

@section('content')
<a href="{{ route('admin.klasy.index') }}">Wróć do listy</a>

<h2>Utwórz nową klasę</h2>

@if($errors->any())
    <div style="color: red">
        <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.klasa.store') }}" method="POST">
    @csrf
    <label>Nazwa Klasy:</label><br>
    <input type="text" name="nazwa" value="{{ old('nazwa') }}"><br><br>
    
    <label>Wychowawca:</label><br>
    <select name="wychowawca_id">
        <option value="">-- Wybierz --</option>
        @foreach($nauczyciele as $n)
            <option value="{{ $n->id }}" {{ old('wychowawca_id') == $n->id ? 'selected' : '' }}>
                {{ $n->imie }} {{ $n->nazwisko }}
            </option>
        @endforeach
    </select><br><br>

    <button type="submit">Zapisz</button>
</form>
@endsection
