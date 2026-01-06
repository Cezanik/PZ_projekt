@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót</a> | 
<a href="{{ route('admin.przedmiot.create') }}">Dodaj przedmiot</a>

<h1>Lista Przedmiotów</h1>

@if(session('success')) <p style="color: green">{{ session('success') }}</p> @endif

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
        </tr>
    </thead>
    <tbody>
        @foreach($przedmioty as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->nazwa }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
