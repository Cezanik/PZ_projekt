@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót</a> | 
<a href="{{ route('admin.przydzial.nauczyciel') }}">Dodaj nowy przydział</a>

<h1>Aktywne Przydziały</h1>

@if(session('success')) <p style="color: green">{{ session('success') }}</p> @endif

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Klasa</th>
            <th>Przedmiot</th>
            <th>Nauczyciel</th>
        </tr>
    </thead>
    <tbody>
        @foreach($przydzialy as $przydzial)
        <tr>
            <td>{{ $przydzial->klasa->nazwa }}</td>
            <td>{{ $przydzial->przedmiot->nazwa }}</td>
            <td>{{ $przydzial->nauczyciel->imie }} {{ $przydzial->nauczyciel->nazwisko }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
