@extends('app.template')

@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ url('surat_keluar/' . $surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tipe Surat --}}
            <div class="mb-3">
                <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                <select class="form-select @error('tipe') is-invalid @enderror" name="tipe">
                    <option value="" disabled>-- pilih tipe surat --</option>
                    <option value="umum" {{ old('tipe', $surat->tipe) == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="permohonan" {{ old('tipe', $surat->tipe) == 'permohonan' ? 'selected' : '' }}>Permohonan</option>
                </select>
                @error('tipe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ditujukan --}}
            <div class="mb-3">
                <label for="ditujukan" class="form-label">Ditujukan <span class="text-danger">*</span></label>
                <select class="form-select @error('ditujukan') is-invalid @enderror" name="ditujukan">
                    <option value="" disabled>-- pilih penerima surat --</option>
                    @foreach ($bagian as $value)
                        <option value="{{ $value->id }}" {{ old('ditujukan', $surat->ditujukan) == $value->id ? 'selected' : '' }}>
                            {{ $value->nama_bagian }}
                        </option>
                    @endforeach
                </select>
                @error('ditujukan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Sifat --}}
            <div class="mb-3">
                <label for="sifat" class="form-label">Sifat <span class="text-danger">*</span></label>
                <select class="form-select @error('sifat') is-invalid @enderror" name="sifat">
                    <option value="" disabled>-- pilih sifat surat --</option>
                    <option value="biasa" {{ old('sifat', $surat->sifat) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="penting" {{ old('sifat', $surat->sifat) == 'penting' ? 'selected' : '' }}>Penting</option>
                    <option value="segera" {{ old('sifat', $surat->sifat) == 'segera' ? 'selected' : '' }}>Segera</option>
                    <option value="amat segera" {{ old('sifat', $surat->sifat) == 'amat segera' ? 'selected' : '' }}>Amat Segera</option>
                </select>
                @error('sifat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Lampiran --}}
            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('lampiran') is-invalid @enderror" name="lampiran" value="{{ old('lampiran', $surat->lampiran) }}">
                @error('lampiran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Perihal --}}
            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" name="perihal" value="{{ old('perihal', $surat->perihal) }}">
                @error('perihal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Surat --}}
            <div class="mb-3">
                <label for="tgl_surat" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tgl_surat') is-invalid @enderror" name="tgl_surat" value="{{ old('tgl_surat', $surat->tgl_surat) }}">
                @error('tgl_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- File Surat (optional saat edit) --}}
            <div class="mb-3">
                <label for="file" class="form-label">Ganti File (opsional)</label>
                <input type="file" class="form-control @error('file') is-invalid @enderror" name="file">
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($surat->file)
                    <p class="form-text text-muted mt-2">File saat ini: <a href="{{ asset('storage/' . $surat->file) }}" target="_blank">Lihat File</a></p>
                @endif
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
    </div>
</div>

@endsection
