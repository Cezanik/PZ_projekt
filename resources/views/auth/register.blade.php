<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
</head>
<body>

    <h2>Rejestracja</h2>

    {{-- Wyświetlanie ogólnych błędów walidacji, jeśli istnieją --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Pole: Login --}}
        <div>
            <label for="login">Login:</label><br>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required>
            @error('login')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <br>

        {{-- Pole: Hasło --}}
        <div>
            <label for="password">Hasło:</label><br>
            <input type="password" id="password" name="password" required>
            @error('password')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <br>

        {{-- Pole: Imię --}}
        <div>
            <label for="imie">Imię:</label><br>
            <input type="text" id="imie" name="imie" value="{{ old('imie') }}" required>
            @error('imie')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <br>

        {{-- Pole: Nazwisko --}}
        <div>
            <label for="nazwisko">Nazwisko:</label><br>
            <input type="text" id="nazwisko" name="nazwisko" value="{{ old('nazwisko') }}" required>
            @error('nazwisko')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <br>       

        <button type="submit">Zarejestruj się</button>
    </form>

    <br>
    <a href="{{ route('login') }}">Powrót do logowania</a>

</body>
</html>
