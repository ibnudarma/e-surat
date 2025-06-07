@extends('app.template')

@section('content')

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'berhasil',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    {{ session()->forget('success') }}
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ url('surat_keluar_create') }}" class="btn btn-primary">Buat Surat Keluar</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <th>No</th>
                <th>Tanggal Surat</th>
                <th>Perihal</th>
                <th>Ditujukan</th>
                <th>Status</th>
                <th>Aksi</th>
            </thead>
            <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($surat_keluar as $value)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $value->tgl_surat }}</td>
                    <td>{{ $value->perihal }}</td>
                    <td>{{ $value->penerima->nama_bagian }}</td>
                    <td><span class="badge rounded-pill text-bg-{{ $value->statusTerakhir->color }}">{{ $value->statusTerakhir->status }}</span></td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Opsi
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('surat_keluar/' . $value->id) }}">detail</a></li>
                                @if ($value->tgl_diterima === null  && $value->noref === null)
                                <li><a class="dropdown-item" href="{{ url('surat_keluar/' . $value->id . '/edit') }}">edit</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ url('storage/' . $value->file) }}" target="_blank">lihat surat</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection