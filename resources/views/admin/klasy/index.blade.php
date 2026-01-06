@extends('layout')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}">Powrót do Dashboardu</a> | 
    <a href="{{ route('admin.klasa.create') }}">Dodaj nową klasę</a>
</div>

<h1>Lista Klas</h1>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ID</th>
            <th>Nazwa Klasy</th>
            <th>Wychowawca</th>
            <th>Akcje</th> </tr>
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
                    <span style="color: gray;">Brak wychowawcy</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.klasa.edit', $klasa->id) }}" style="text-decoration: none; color: blue; margin-right: 10px;">
                    [Edytuj]
                </a>

                <form action="{{ route('admin.klasa.destroy', $klasa->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tę klasę?');">
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
