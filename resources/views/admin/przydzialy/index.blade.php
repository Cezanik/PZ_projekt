@extends('layout')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('dashboard') }}">Powrót</a> | 
    <a href="{{ route('admin.przydzial.nauczyciel') }}">Dodaj nowy przydział</a> |
    Widoki tabel: 
    <a href="{{ route('admin.uczen.klasa.index') }}">Uczeń-Klasa</a> | 
    <a href="{{ route('admin.rodzic.uczen.index') }}">Rodzic-Uczeń</a>
</div>

<h1>Aktywne Przydziały (Nauczyciel - Przedmiot - Klasa)</h1>

<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: left;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Klasa</th>
            <th>Przedmiot</th>
            <th>Nauczyciel</th>
            <th>Akcje</th> </tr>
    </thead>
    <tbody>
        @foreach($przydzialy as $przydzial)
        <tr>
            <td>{{ $przydzial->klasa->nazwa }}</td>
            <td>{{ $przydzial->przedmiot->nazwa }}</td>
            <td>{{ $przydzial->nauczyciel->imie }} {{ $przydzial->nauczyciel->nazwisko }}</td>
            <td>
                <a href="{{ route('admin.przydzial.edit', $przydzial->id) }}" style="color: blue; margin-right: 10px;">
                    [Edytuj]
                </a>

                <form action="{{ route('admin.przydzial.destroy', $przydzial->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy usunąć ten przydział?');">
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
