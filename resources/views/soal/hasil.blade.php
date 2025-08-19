@extends('template.main')

@section('content')
<div class="container main-container text-center mt-5 mb-5">
    <div class="card shadow mx-auto" style="max-width: 650px">
        <div class="card-body">
            <h4 class="fw-bold text-success mb-3">✅ Selamat, jawaban Anda berhasil tersimpan!</h4>

            <p><strong>Nama Peserta:</strong> {{ $users->name }}</p>
            <p><strong>Total Soal:</strong> {{ $result['total_question'] }}</p>
            <p><strong>Benar:</strong> {{ $result['total_correct'] }}</p>
            <p><strong>Salah:</strong> {{ $result['total_wrong'] }}</p>
            <h2 class="fw-bold text-primary">Skor: {{ $result['score'] }}</h2>

            <a href="{{ route('home') }}" class="btn btn-success mt-3 px-4 py-2">Kembali ke Home</a>
        </div>
    </div>
</div>
@endsection
