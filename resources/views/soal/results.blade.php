{{-- resources/views/soal/results.blade.php --}}
@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading" style="display: none;">
    <div class="spinner"></div>
</div>

{{-- Header --}}
<div class="bg-header" style="background-color: rgb(255, 165, 0);">
    <div class="container text-center text-white">
        <h2>Hasil Tes</h2>
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
         preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z"
              style="fill: rgb(255, 165, 0);"></path>
    </svg>
</div>

<div class="container main-container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card rounded-1 shadow-sm mb-4">
                <div class="card-header bg-transparent text-center fw-bold">
                    <i class="fa fa-check-circle text-warning"></i> Ringkasan Skor
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Benar</span>
                            <span class="fw-bold">{{ $benar }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Salah</span>
                            <span class="fw-bold">{{ $salah }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Kosong</span>
                            <span class="fw-bold">{{ $kosong }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Skor Akhir</span>
                            <span class="fw-bold">{{ $score }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
                <div class="container">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <a href="{{ route('soal.index') }}"
                               class="btn btn-md btn-warning text-uppercase">
                                Ulangi Tes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/') }}"
                               class="btn btn-md btn-secondary text-uppercase">
                                Kembali ke Beranda
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('js-extra')
{{-- Jika perlu, tambahkan scripts --}}
@endsection

@section('css-extra')
{{-- Jika perlu, tambahkan style khusus --}}
@endsection
