@extends('layout')

@section('content')
<a href="{{ route('admin.przedmioty.index') }}">Wróć do listy</a>

<h2>Dodaj Przedmiot</h2>

@if($errors->any())
    <div style="color: red">
        <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.przedmiot.store') }}" method="POST"> 
    @csrf
    <label>Nazwa przedmiotu:</label><br>
    <input type="text" name="nazwa" value="{{ old('nazwa') }}"><br><br>
    <button type="submit">Zapisz</button>
</form>
@endsection
