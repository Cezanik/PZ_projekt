@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Historia zmian oceny</h3>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Wróć</a>
        </div>
        <div class="card-body">
            
            <div class="alert alert-light border">
                <strong>Aktualna ocena:</strong> <span class="badge bg-primary fs-5">{{ $ocena->wartosc }}</span><br>
                <strong>Przedmiot:</strong> {{ $ocena->przedmiot->nazwa }}<br>
                <strong>Dziecko:</strong> {{ $ocena->uczen->imie }} {{ $ocena->uczen->nazwisko }}
            </div>

            <h5 class="mt-4">Rejestr zmian:</h5>
            
            @if($historia->isEmpty())
                <p class="text-muted">Brak zarejestrowanych zmian dla tej oceny. To jest wersja oryginalna.</p>
            @else
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Data Zmiany</th>
                            <th>Poprzednia Wartość</th>
                            <th>Poprzedni Opis</th>
                            <th>Powód Zmiany</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historia as $wpis)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($wpis->data_zmiany)->format('Y-m-d H:i') }}</td>
                            <td class="text-danger fw-bold">{{ $wpis->stara_wartosc }}</td>
                            <td>{{ $wpis->stara_opis }}</td>
                            <td>{{ $wpis->powod_zmiany }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
