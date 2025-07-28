@extends('template.main')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">Erorrrr 😵</h1>
    <p class="lead">{{ $message ?? 'Soal tidak ditemukan atau belum ditentukan.' }}</p>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="{{ route('soal.index') }}" class="btn btn-primary">Mulai Tes</a>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary">Kembali ke Beranda</a>
    </div>
</div>
@endsection
