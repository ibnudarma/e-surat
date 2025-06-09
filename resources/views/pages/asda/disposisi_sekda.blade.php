@extends('app.template')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-title">Disposisi Dari Sekda</div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <th>No</th>
                <th>Nomor agenda</th>
                <th>Sifat</th>
                <th>Intruksi</th>
                <th>Catatan</th>
            </thead>
            <tbody>
                @php
                    $nomor = 1;
                @endphp
                @foreach ($disposisi as $value)
                    <tr>
                        <td>{{ $nomor++ }}</td>
                        <td>{{ $value->nomor_agenda }}</td>
                        <td>{{ $value->sifat }}</td>
                        <td>{{ $value->intruksi }}</td>
                        <td>{{ $value->catatan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection