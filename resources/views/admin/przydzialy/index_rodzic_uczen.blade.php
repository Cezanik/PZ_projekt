@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót do Dashboardu</a> | 
<a href="{{ route('admin.przydzialy.index') }}">Wróć do listy przydziałów</a> |
<a href="{{ route('admin.rodzic.uczen.create') }}">Powiąż rodzica z uczniem</a>

<h1>Lista: Rodzic - Uczeń</h1>

<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: left;">
    <thead>
        <tr>
            <th>Rodzic</th>
            <th>Dzieci (Uczniowie)</th>
            <th>Akcje</th> </tr>
    </thead>
    <tbody>
        @forelse($rodzice as $rodzic)
            <tr>
                <td>{{ $rodzic->imie }} {{ $rodzic->nazwisko }} <br> ({{ $rodzic->login }})</td>
                <td>
                    @foreach($rodzic->dzieci as $dziecko)
                        <div style="margin-bottom: 5px; border-bottom: 1px solid #eee;">
                            {{ $dziecko->imie }} {{ $dziecko->nazwisko }}
                        </div>
                    @endforeach
                </td>
                <td>
                    @foreach($rodzic->dzieci as $dziecko)
                        <div style="margin-bottom: 5px;">
                            <a href="{{ route('admin.rodzic.uczen.edit', [$rodzic->id, $dziecko->id]) }}">Edytuj</a>

                            <form action="{{ route('admin.rodzic.uczen.destroy', [$rodzic->id, $dziecko->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy usunąć powiązanie rodzica z tym uczniem?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:red; border:none; background:none; cursor:pointer; text-decoration:underline;">Usuń</button>
                            </form>
                        </div>
                    @endforeach
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Brak powiązań.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
