@extends('template.main')

@section('content')
<!-- Header -->
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h2>TES SELESAI</h2>
    </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>

<!-- Isi Halaman -->
<div class="container main-container text-center mt-5 mb-5">
    <div class="card shadow mx-auto" style="max-width: 600px">
        <div class="card-body">

            @if(session('simpan_success'))
                <h4 class="fw-bold text-success mb-3">Selamat!</h4>
                <p class="mb-4">Jawaban Anda telah tersimpan dengan baik.</p>
            @else
                <h4 class="fw-bold text-danger mb-3">Gagal!</h4>
                <p class="mb-4">Jawaban Anda gagal tersimpan. Silakan hubungi pengawas.</p>
            @endif

            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('soal.skor') }}" class="btn btn-primary px-4 py-2">
                    <i class="fa fa-bar-chart me-2"></i> Lihat Skor
                </a>
                <a href="{{ route('index') }}" class="btn btn-secondary px-4 py-2">
                    <i class="fa fa-home me-2"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
