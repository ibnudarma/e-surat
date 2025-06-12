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

<div class="card shadow-sm rounded">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center mb-0">
      <h5 class="card-title mb-0 fw-bold">Filter Dokumen</h5>
      <button class="btn btn-primary" id="toggleFilter">Show/Hide</button>
    </div>
  </div>
  <div class="card-body" id="filterBody" style="display: none;">
    <form>
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label for="startDate" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="startDate" value="2025-05-01" required>
        </div>
        <div class="col-md-3">
          <label for="endDate" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="endDate" value="2025-06-11" required>
        </div>
        <div class="col-md-6">
          <label for="noSurat" class="form-label">No Surat</label>
          <input type="text" class="form-control" id="noSurat" placeholder="">
        </div>
      </div>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label for="perihal" class="form-label">Perihal Surat</label>
          <input type="text" class="form-control" id="perihal" placeholder="">
        </div>
        <div class="col-md-6">
          <label for="jenisSurat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
          <select class="form-select" id="jenisSurat" required>
            <option selected>Semua Jenis Surat</option>
            <!-- Tambahkan opsi lainnya di sini -->
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
    <div class="card-header">
        <a href="{{ url('surat_keluar_create') }}" class="btn btn-primary">Buat Surat Keluar</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <th>No</th>
                <th>Tanggal Surat</th>
                <th>Nomor Surat</th>
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
                    <td>{{ $value->nomor }}</td>
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