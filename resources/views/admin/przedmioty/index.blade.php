@extends('layout')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}">Powrót do Dashboardu</a> | 
    <a href="{{ route('admin.przedmiot.create') }}">Dodaj nowy przedmiot</a>
</div>

<h1>Lista Przedmiotów</h1>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Nazwa Przedmiotu</th>
            <th>Akcje</th> </tr>
    </thead>
    <tbody>
        @foreach($przedmioty as $przedmiot)
        <tr>
            <td>{{ $przedmiot->id }}</td>
            <td>{{ $przedmiot->nazwa }}</td>
            <td>
                <a href="{{ route('admin.przedmiot.edit', $przedmiot->id) }}" style="text-decoration: none; color: blue; margin-right: 10px;">
                    [Edytuj]
                </a>

                <form action="{{ route('admin.przedmiot.destroy', $przedmiot->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten przedmiot?');">
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
