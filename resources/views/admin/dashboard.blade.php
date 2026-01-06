@extends('layout')

@section('content')
    <h1>Panel Administratora</h1>

    <hr>
    <h2>Dodaj Użytkownika</h2>
    <form action="/admin/user" method="POST">
        @csrf
        <label>Login:</label> <input type="text" name="login"><br>
        <label>Hasło:</label> <input type="text" name="password"><br>
        <label>Imię:</label> <input type="text" name="imie"><br>
        <label>Nazwisko:</label> <input type="text" name="nazwisko"><br>
        <label>Rola:</label>
        <select name="role">
            <option value="uczen">Uczeń</option>
            <option value="nauczyciel">Nauczyciel</option>
            <option value="rodzic">Rodzic</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <button type="submit">Utwórz konto</button>
    </form>

    <hr>
    <h2>Utwórz Klasę</h2>
    <form action="/admin/klasa" method="POST">
        @csrf
        <label>Nazwa Klasy (np. 1A):</label> <input type="text" name="nazwa"><br>
        <label>ID Wychowawcy:</label> <input type="number" name="wychowawca_id"><br><br>
        <button type="submit">Utwórz klasę</button>
    </form>

    <hr>
    <h2>Dodaj Przedmiot</h2>
<form action="/admin/przedmiot" method="POST"> 
    @csrf
    <label>Nazwa przedmiotu:</label> 
    <input type="text" name="nazwa" required>
    <br><br>
    <button type="submit">Dodaj przedmiot</button>
</form>

    <hr>
    <h2>Przydział: Nauczyciel -> Przedmiot -> Klasa</h2>
    <form action="/admin/przydzial" method="POST">
        @csrf
        <label>ID Klasy:</label> <input type="number" name="klasa_id"><br>
        <label>ID Przedmiotu:</label> <input type="number" name="przedmiot_id"><br>
        <label>ID Nauczyciela:</label> <input type="number" name="nauczyciel_id"><br><br>
        <button type="submit">Przypisz</button>
    </form>

    <hr>
    <h2>Przypisz Ucznia do Klasy</h2>
    <form action="/admin/przypisz-ucznia" method="POST"> @csrf
        <label>ID Klasy:</label> <input type="number" name="klasa_id"><br>
        <label>ID Ucznia:</label> <input type="number" name="uczen_id"><br>
        <input type="hidden" name="uczniowie_ids[]" id="hidden_student_id"> 
        <script>
            // Prosty skrypt przepisujący wartość do tablicy (dla zgodności z kontrolerem)
            document.querySelector('input[name="uczen_id"]').addEventListener('input', function(e) {
                document.getElementById('hidden_student_id').value = e.target.value;
            });
        </script>
        <br>
        <button type="submit">Dodaj ucznia do klasy</button>
    </form>

    <hr>
    <h2>Powiąż Rodzica z Dzieckiem</h2>
    <form action="/admin/rodzic-uczen" method="POST"> @csrf
        <label>ID Rodzica:</label> <input type="number" name="rodzic_id"><br>
        <label>ID Ucznia:</label> <input type="number" name="uczen_id"><br><br>
        <button type="submit">Powiąż</button>
    </form>

@endsection
