@extends('layout')

@section('content')
    <a href="{{ route('dashboard') }}">&larr; Powrót do panelu</a>
    <h2>Dodaj Przedmiot</h2>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="/admin/przedmioty" method="POST"> 
        @csrf
        <label>Nazwa przedmiotu:</label> 
        <input type="text" name="nazwa" required>
        <br><br>
        <button type="submit">Dodaj przedmiot</button>
    </form>
@endsection
