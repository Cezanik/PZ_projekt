@extends('layout')

@section('content')
<a href="{{ route('dashboard') }}">Powrót do Dashboardu</a> | 
<a href="{{ route('admin.przydzialy.index') }}">Wróć do listy przydziałów</a> |
<a href="{{ route('admin.uczen.klasa.create') }}">Przypisz ucznia do klasy</a>

<h1>Lista: Uczeń - Klasa</h1>

<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: left;">
    <thead>
        <tr>
            <th>Imię i Nazwisko Ucznia</th>
            <th>Login</th>
            <th>Przypisana Klasa</th>
            <th>Akcje</th> </tr>
    </thead>
    <tbody>
        @forelse($uczniowie as $uczen)
            <tr>
                <td>{{ $uczen->imie }} {{ $uczen->nazwisko }}</td>
                <td>{{ $uczen->login }}</td>
                <td>
                    @foreach($uczen->klasaUcznia as $klasa)
                        <div style="margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                            {{ $klasa->nazwa }}
                        </div>
                    @endforeach
                </td>
                <td>
                    @foreach($uczen->klasaUcznia as $klasa)
                        <div style="margin-bottom: 5px; padding-bottom: 5px;">
                            <a href="{{ route('admin.uczen.klasa.edit', [$uczen->id, $klasa->id]) }}">Edytuj</a>
                            
                            <form action="{{ route('admin.uczen.klasa.destroy', [$uczen->id, $klasa->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Czy odłączyć ucznia od tej klasy?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:red; border:none; background:none; cursor:pointer; text-decoration:underline;">Usuń</button>
                            </form>
                        </div>
                    @endforeach
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Brak danych.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
