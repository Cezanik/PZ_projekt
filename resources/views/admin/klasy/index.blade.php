@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót</a> | 
<a href="{{ route('admin.klasa.create') }}">Dodaj klasę</a>

<h1>Lista Klas</h1>

@if(session('success')) <p style="color: green">{{ session('success') }}</p> @endif

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Wychowawca</th>
        </tr>
    </thead>
    <tbody>
        @foreach($klasy as $klasa)
        <tr>
            <td>{{ $klasa->id }}</td>
            <td>{{ $klasa->nazwa }}</td>
            <td>
                @if($klasa->wychowawca)
                    {{ $klasa->wychowawca->imie }} {{ $klasa->wychowawca->nazwisko }}
                @else
                    Brak
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
