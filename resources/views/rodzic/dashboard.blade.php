@extends('layout')

@section('content')
<div class="container mt-4">
    <h1>Witaj, {{ Auth::user()->imie }}!</h1>
    <p class="text-muted">Panel Rodzica</p>
    <hr>

    <h3>Twoje Dzieci:</h3>

    @if($dzieci->isEmpty())
        <div class="alert alert-warning">
            Nie masz przypisanych żadnych dzieci w systemie.
        </div>
    @else
        <div class="row">
            @foreach($dzieci as $dziecko)
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $dziecko->imie }} {{ $dziecko->nazwisko }}</h5>
                        <p class="card-text">
                            Login: {{ $dziecko->login }}<br>
                            {{-- Możesz tu dodać klasę dziecka jeśli masz taką relację łatwo dostępną --}}
                        </p>
                        <a href="{{ route('rodzic.dziecko.oceny', $dziecko->id) }}" class="btn btn-primary">
                            <i class="bi bi-journal-check"></i> Zobacz Oceny
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
