@extends('layout')

@section('content')
<div class="container">
    <h1>Witaj, {{ Auth::user()->imie }}!</h1>
    <p>Jesteś zalogowany jako uczeń.</p>

    <hr>

    <h3>Twoje Klasy:</h3>

    @if($klasy->isEmpty())
        <p>Nie jesteś przypisany do żadnej klasy.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Nazwa Klasy</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($klasy as $klasa)
                <tr>
                    <td>
                        <strong>{{ $klasa->nazwa }}</strong>
                    </td>
                    <td>
                        <a href="{{ route('uczen.oceny.index') }}" 
                           style="background-color: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px;">
                           Zobacz swoje oceny
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
