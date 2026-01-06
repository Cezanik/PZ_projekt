@extends('layout')

@section('content')
<a href="{{ route('admin.przydzialy.index') }}">Wróć do listy</a>

<h2>Nowy Przydział</h2>

@if($errors->any())
    <div style="color: red">
        <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.przydzial.store') }}" method="POST">
    @csrf
    <label>Klasa:</label><br>
    <select name="klasa_id">
        @foreach($klasy as $k)
            <option value="{{ $k->id }}" {{ old('klasa_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nazwa }}
            </option>
        @endforeach
    </select><br><br>

    <label>Przedmiot:</label><br>
    <select name="przedmiot_id">
        @foreach($przedmioty as $p)
            <option value="{{ $p->id }}" {{ old('przedmiot_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nazwa }}
            </option>
        @endforeach
    </select><br><br>

    <label>Nauczyciel:</label><br>
    <select name="nauczyciel_id">
        @foreach($nauczyciele as $n)
            <option value="{{ $n->id }}" {{ old('nauczyciel_id') == $n->id ? 'selected' : '' }}>
                {{ $n->imie }} {{ $n->nazwisko }}
            </option>
        @endforeach
    </select><br><br>

    <button type="submit">Przypisz</button>
</form>
@endsection
