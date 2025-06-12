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

<div class="card shadow-sm rounded">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center mb-0">
      <h5 class="card-title mb-0 fw-bold">Filter Surat</h5>
      <button class="btn btn-primary" id="toggleFilter">Show/Hide</button>
    </div>
  </div>
  <div class="card-body" id="filterBody" style="display: none;">
<form method="GET" action="{{ url('surat_masuk') }}">
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <label for="startDate" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
      <input type="date" class="form-control" id="startDate" name="startDate"
        value="{{ $request->startDate }}">
    </div>
    <div class="col-md-3">
      <label for="endDate" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
      <input type="date" class="form-control" id="endDate" name="endDate"
        value="{{ $request->endDate }}">
    </div>
    <div class="col-md-6">
      <label for="jenisSurat" class="form-label">Bagian <span class="text-danger">*</span></label>
      <select class="form-select" id="bagian" name="bagian">
        <option value="">Semua Bagian</option>
        @foreach ($bagian as $value)
          <option value="{{ $value->id }}" {{ $request->bagian == $value->id ? 'selected' : '' }}>
            {{ $value->nama_bagian }}
          </option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <label for="nomor" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="nomor" name="nomor" value="{{ $request->nomor }}">
    </div>
    <div class="col-md-6">
      <label for="tipeSurat" class="form-label">Tipe Surat <span class="text-danger">*</span></label>
      <select class="form-select" id="tipeSurat" name="tipe">
        <option value="">Semua Tipe Surat</option>
        <option value="umum" {{ $request->tipe == 'umum' ? 'selected' : '' }}>Umum</option>
        <option value="permohonan" {{ $request->tipe == 'permohonan' ? 'selected' : '' }}>Permohonan</option>
      </select>
    </div>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-primary px-4">Cari</button>
  </div>
</form>

  </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
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
                    @elseif ($value->disposisiSekda && $value->disposisiSekda->tgl_diterima === null)
                        <a href="{{ url('asda/disposisi_sekda/diterima/' . $value->disposisiSekda->id) }}" class="btn btn-success btn-sm">Terima</a>
                    @elseif ($value->kartuDisposisi && $value->kartuDisposisi->tgl_diterima_asda === null)
                        <a href="{{ url('asda/kartu_disposisi/diterima/' . $value->kartuDisposisi->id) }}" class="btn btn-success btn-sm">Terima</a>
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
                                @if ($value->tipe === 'permohonan' && $value->disposisiAsda === null && $value->kartuDisposisi === null)
                                    <li><a class="dropdown-item" href="{{ url('asda/disposisi/create/' . $value->id) }}">Disposisikan</a></li>
                                @elseif ($value->tipe === 'permohonan' && $value->disposisiAsda !== null)
                                    <li><a class="dropdown-item" href="{{ url('asda/disposisi/view/' . $value->disposisiAsda->id) }}">Lihat Lembar Disposisi</a></li>
                                @endif
                                @if ($value->tipe === 'permohonan' && $value->kartuDisposisi)
                                    <li><a class="dropdown-item" href="{{ url('storage/' . $value->KartuDisposisi->file_nota_dinas) }}" target="_blank">Lihat Nota Dinas</a></li>
                                    @if ($value->kartuDisposisi && $value->kartuDisposisi->surat_permohonan_pencairan_id === null)
                                        <li><a class="dropdown-item" href="{{ url('asda/permohonan_pencairan/' . $value->kartuDisposisi->id) }}">Buat Permohonan Pencairan</a></li> 
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