@extends('layout')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">

    <div style="margin-bottom: 25px;">
        <a href="{{ route('nauczyciel.przydzialy.index') }}" style="text-decoration: none; color: #555; font-size: 14px;">
            &larr; Wróć do listy przydziałów
        </a>
        <h2 style="margin-top: 10px; color: #333;">📋 Moja historia zmian</h2>
        <p style="color: #666;">Rejestr wszystkich edycji i przywróceń ocen wykonanych przez Ciebie.</p>
    </div>

    <div style="box-shadow: 0 0 10px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f8f9fa; color: #495057;">
                <tr>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Data</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Uczeń</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Przedmiot</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Zmiana</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Powód</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historia as $wpis)
                    <tr style="border-bottom: 1px solid #eee; background-color: #fff;">
                        <td style="padding: 12px; color: #666; font-size: 13px;">
                            {{ \Carbon\Carbon::parse($wpis->data_zmiany)->format('d.m.Y H:i') }}
                        </td>
                        <td style="padding: 12px; font-weight: bold;">
                            {{ $wpis->ocena->uczen->imie ?? 'Usunięty' }} {{ $wpis->ocena->uczen->nazwisko ?? '' }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $wpis->ocena->przedmiot->nazwa ?? '-' }}
                        </td>
                        <td style="padding: 12px;">
                            <span style="color: #dc3545;">{{ $wpis->stara_wartosc }}</span>
                            &rarr;
                            <span style="color: #198754; font-weight: bold;">{{ $wpis->ocena->wartosc }}</span>
                        </td>
                        <td style="padding: 12px; font-style: italic; color: #555;">
                            {{ $wpis->powod_zmiany }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #666;">
                            Brak historii zmian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
