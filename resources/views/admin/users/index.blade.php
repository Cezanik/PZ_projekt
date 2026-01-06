@extends('layout')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}">Powrót do Dashboardu</a> | 
    <a href="{{ route('admin.user.create') }}">Dodaj nowego użytkownika</a>
</div>

<h1>Lista Użytkowników</h1>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Imię i Nazwisko</th>
            <th>Login</th>
            <th>Rola</th>
            <th>Akcje</th> </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->imie }} {{ $user->nazwisko }}</td>
            <td>{{ $user->login }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                <a href="{{ route('admin.user.edit', $user->id) }}" style="text-decoration: none; color: blue; margin-right: 10px;">
                    [Edytuj]
                </a>

                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red; border: none; background: none; cursor: pointer; text-decoration: underline;">
                        [Usuń]
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
