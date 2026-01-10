@extends('layout')

@section('content')
<div class="container mt-4">
    <h1>Witaj, {{ Auth::user()->imie }}!</h1>
    <p class="text-muted">Jesteś zalogowany jako uczeń.</p>

    <hr>

    <h3>Twoje Klasy:</h3>

    @if($klasy->isEmpty())
        <div class="alert alert-warning">
            Nie jesteś przypisany do żadnej klasy.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nazwa Klasy</th>
                        <th style="width: 200px;">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($klasy as $klasa)
                    <tr>
                        <td class="align-middle">
                            <strong>{{ $klasa->nazwa }}</strong>
                        </td>
                        <td>
                          
                            <a href="{{ route('uczen.oceny.klasa', ['klasa' => $klasa->id]) }}" 
                               class="btn btn-primary btn-sm">
                               <i class="bi bi-journal-text"></i> Zobacz oceny
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
