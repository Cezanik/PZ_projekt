<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>e-Dziennik (Bez stylu)</title>
</head>
<body>

    @auth
        <p>Zalogowany jako: {{ Auth::user()->login }} ({{ Auth::user()->role }})</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Wyloguj się</button>
        </form>
        <hr>
        <a href="{{ route('dashboard') }}">Wróć do Panelu Głównego</a>
        <hr>
    @endauth

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

</body>
</html>
