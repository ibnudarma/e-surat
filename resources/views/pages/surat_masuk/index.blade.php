@extends('app.template')

@section('content')

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'berhasil masuk',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    {{ session()->forget('success') }}
@endif

<div class="card">
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
            @foreach ($surat_masuk as $value)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $value->tgl_surat }}</td>
                    <td>{{ $value->perihal }}</td>
                    <td>{{ $value->penerima->nama_bagian }}</td>
                    <td>
                        @if ($value->tgl_diterima === null)
                          <span class="badge rounded-pill text-bg-warning">belum diterima</span>  
                        @else
                          <span class="badge rounded-pill text-bg-success">diterima</span>     
                        @endif
                    </td>
                    <td>
                    @if ($value->tgl_diterima === null)
                        <a href="{{ url('surat_masuk/diterima/' . $value->id) }}" class="btn btn-success btn-sm">Terima</a>
                    @else   
                        <div class="dropdown">
                            <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Opsi
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('surat_keluar/' . $value->id) }}">detail</a></li>
                                <li><a class="dropdown-item" href="{{ url('storage/' . $value->file) }}" target="_blank">lihat surat</a></li>
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