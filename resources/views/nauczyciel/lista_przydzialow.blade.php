@extends('layout')

@section('content')
<div style="padding: 20px;">
    <h2>Lista Twoich Przydziałów (Klasy i Przedmioty)</h2>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Klasa</th>
                <th>Przedmiot</th>
                <th>Dziennik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($przydzialy as $przydzial)
                <tr>
                    <td>{{ $przydzial->klasa->nazwa }}</td>
                    <td>{{ $przydzial->przedmiot->nazwa }}</td>
                    <td>
                        <a href="{{ route('nauczyciel.arkusz.show', ['klasa' => $przydzial->klasa->id, 'przedmiot' => $przydzial->przedmiot->id]) }}">
                            <button style="cursor: pointer;">Otwórz arkusz ocen</button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Brak przypisanych klas i przedmiotów.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
