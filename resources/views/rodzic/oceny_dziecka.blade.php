@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Oceny: {{ $child->imie }} {{ $child->nazwisko }}</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Wróć do listy dzieci</a>
    </div>

    @if($oceny->isEmpty())
        <div class="alert alert-info">To dziecko nie ma jeszcze żadnych ocen.</div>
    @else
        @foreach($oceny as $przedmiotNazwa => $ocenyZPrzedmiotu)
            <div class="card mb-3">
                <div class="card-header bg-light fw-bold">
                    {{ $przedmiotNazwa }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Ocena</th>
                                <th>Opis</th>
                                <th>Nauczyciel</th>
                                <th>Data</th>
                                <th style="width: 100px;">Historia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ocenyZPrzedmiotu as $ocena)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">{{ $ocena->wartosc }}</span>
                                </td>
                                <td>{{ $ocena->opis ?? '-' }}</td>
                                <td>{{ $ocena->nauczyciel->imie ?? '' }} {{ $ocena->nauczyciel->nazwisko ?? '' }}</td>
                                <td>{{ $ocena->created_at->format('Y-m-d') }}</td>
                                <td>
                                    {{-- Przycisk do historii zmian --}}
                                    <a href="{{ route('rodzic.ocena.historia', $ocena->id) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Zobacz historię zmian">
                                        <i class="bi bi-clock-history"></i> Zmiany
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
