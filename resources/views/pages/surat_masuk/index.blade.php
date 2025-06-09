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
                    <td>{{ $value->tipe }}</td>
                    <td>{{ $value->perihal }}</td>
                    <td>{{ $value->pengirim->nama_bagian }}</td>
                    <td>
                        @if($value->dibalas !== null)
                        <span class="badge rounded-pill text-bg-info">dibalas</span>  
                        @elseif ($value->tgl_diterima === null)
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
                                <li><a class="dropdown-item" href="{{ url('surat_masuk/' . $value->id) }}">detail</a></li>
                                <li><a class="dropdown-item" href="{{ url('storage/' . $value->file) }}" target="_blank">lihat surat</a></li>
                                @if ($value->dibalas === null && $value->tipe === 'umum')
                                <li><a class="dropdown-item" href="{{ url('surat_masuk/balas/' . $value->id) }}">balas surat</a></li>
                                @endif
                                @if ($value->tipe === 'permohonan')
                                    @if (auth()->user()->bagian->id === 1)
                                        @if ($value->kartuDisposisi === null)
                                            <li><a class="dropdown-item" href="{{ url('kabag/kartu_disposisi/' . $value->id) }}">Buat Kartu Disposisi</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ url('kabag/kartu_disposisi_view/' . $value->id) }}">Lihat Kartu Disposisi</a></li>            
                                        @endif
                                    @elseif (auth()->user()->bagian->id === 2)    
                                        <li><a class="dropdown-item" href="{{ url('disposisi/sekda/' . $value->id) }}">Disposisikan</a></li>
                                    @elseif (auth()->user()->bagian->id === 3)    
                                        <li><a class="dropdown-item" href="{{ url('disposisi/asda/' . $value->id) }}">Disposisikan</a></li>
                                    @endif
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