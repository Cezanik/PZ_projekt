@extends('layout')

@section('content')
    <h1>Logowanie</h1>
    
    <form action="{{ route('login') }}" method="POST">
        @csrf
        
        <label>Login:</label><br>
        <input type="text" name="login" required><br><br>
        
        <label>Hasło:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Zaloguj</button>
    </form>
@endsection
