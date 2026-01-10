@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Oceny - Klasa: {{ $klasa->nazwa }}</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Wróć do pulpitu</a>
    </div>

    @if($oceny->isEmpty())
        <div class="alert alert-info">
            Brak wystawionych ocen w systemie.
        </div>
    @else
        @foreach($oceny as $przedmiotNazwa => $ocenyZPrzedmiotu)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">{{ $przedmiotNazwa }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Ocena</th>
                                    <th>Opis</th>
                                    <th>Nauczyciel</th>
                                    <th style="width: 150px;">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ocenyZPrzedmiotu as $ocena)
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <span class="badge bg-primary fs-6">{{ $ocena->wartosc }}</span>
                                        </td>
                                        <td>{{ $ocena->opis ?? '-' }}</td>
                                        <td>
                                            @if($ocena->nauczyciel)
                                                {{ $ocena->nauczyciel->imie }} {{ $ocena->nauczyciel->nazwisko }}
                                            @else
                                                <span class="text-muted">Nieznany</span>
                                            @endif
                                        </td>
                                        <td>{{ $ocena->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
