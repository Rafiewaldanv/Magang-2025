@extends('template.main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<!-- Header Style Sama -->
<div class="bg-header" style="background-color: rgb(255, 165, 0);">
    <div class="container text-center text-white">
        <h2 class="mb-0">Selamat Datang di Tes Online</h2>
        <p class="mt-2">Silakan klik tombol di bawah untuk memulai tes.</p>
    </div>
</div>


<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" style="fill: rgb(255, 165, 0);"></path>
    </svg>
</div>


<!-- Isi Halaman -->
<div class="container main-container text-center mt-5 mb-5">
    <div class="card shadow mx-auto" style="max-width: 600px">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Mulai Tes</h4>
            <p class="mb-4">Pastikan kamu sudah siap dan koneksi internet stabil sebelum memulai.</p>
            <a href="{{ route('soal.index') }}" class="btn btn-primary text-uppercase mt-3 px-4 py-2">Mulai Tes Sekarang</a>
            

        </div>
    </div>
</div>
@endsection
