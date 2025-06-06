@extends('app.template')

@section('content')

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Masuk',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

<div class="card">
    <div class="card-body">
    <p class="mb-0">Selamat Datang <b>{{ auth()->user()->profile->nama }}</b></p>
    </div>
</div>

@endsection