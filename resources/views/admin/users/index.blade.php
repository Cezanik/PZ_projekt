@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót do panelu</a> | 
<a href="{{ route('admin.user.create') }}">Dodaj nowego użytkownika</a>

<h1>Lista Użytkowników</h1>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Imię i Nazwisko</th>
            <th>Rola</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->login }}</td>
            <td>{{ $user->imie }} {{ $user->nazwisko }}</td>
            <td>{{ $user->role }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
