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
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    {{ session()->forget('error') }}
@endif

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <th>No</th>
                <th>Tanggal Surat</th>
                <th>Nomor Surat</th>
                <th>Tipe Surat</th>
                <th>Perihal</th>
                <th>Pengirim</th>
                <th>Status</th>
                <th>Aksi</th>
            </thead>
            <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($surat_masuk as $value)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $value->tgl_surat }}</td>
                    <td>{{ $value->nomor }}</td>
                    <td>{{ $value->tipe }}</td>
                    <td>{{ $value->perihal }}</td>
                    <td>{{ $value->pengirim->nama_bagian }}</td>
                    <td><span class="badge rounded-pill text-bg-{{ $value->statusTerakhir->color }}">{{ $value->statusTerakhir->status }}</span></td>
                    <td>
                    @if ($value->tgl_diterima === null)
                        <a href="{{ url('surat_masuk/diterima/' . $value->id) }}" class="btn btn-success btn-sm">Terima</a>
                    @else   
                        <div class="dropdown">
                            <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Opsi
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('surat_masuk/' . $value->id) }}">detail</a></li>
                                @if ($value->dibalas === null && $value->tipe === 'umum')
                                    <li><a class="dropdown-item" href="{{ url('surat_masuk/balas/' . $value->id) }}">balas surat</a></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection