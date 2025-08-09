@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading" style="display: none;">
    <div class="spinner"></div>
</div>

<div class="bg-header" style="background-color: rgb(255, 165, 0);">
    <div class="container text-center text-white">
        <h2 id="timer">00:00</h2>
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" style="fill: rgb(255, 165, 0);"></path>
    </svg>
</div>
<div class="container main-container">
    @if($selection != null && strtotime('now') < strtotime($selection->test_time))
        <div class="row">
            <div class="col-12 mb-2">
                <div class="alert alert-danger text-center">
                    Tes akan dimulai pada <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
                </div>
            </div>
        </div>
    @endif

    @if($selection == null || strtotime('now') >= strtotime($selection->test_time))
    <div id="questmsdt" class="row" style="margin-bottom:100px">
        <!-- Navigasi -->
        <div class="col-12 col-md-4 mb-md-0">
            <div class="card">
                <div class="card-header fw-bold text-center">Navigasi Soal</div>
                <div class="card-body">
                    <form id="form" method="post" action="/tes/{{ $path }}/store">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}">
                        <input type="hidden" name="packet_id" value="{{ $packet->id }}">
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        <input type="hidden" name="jumlah_soal" id="jumlah_soal" value="{{ $jumlah_soal }}">
                        <input type="hidden" name="part" id="part" value="{{ $part }}">
                        <div id="soal-container"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Soal -->
        <div class="col-12 col-md-8">
            <div class="card soal rounded-1 mb-3">
                <div class="soal_number card-header bg-transparent">
                    <i class="fa fa-edit"></i> <span class="num fw-bold">Soal</span>
                </div>
                <div class="card-body s">
                    <!-- Soal dan Opsi akan dimuat lewat JS -->
                </div>
            </div>

          
            <a type="button" id="prev" style="display:none;font-size:1rem" class="btn btn-sm btn-warning">Sebelumnya</a>
            <a type="button" id="next" style="font-size:1rem;float: right;" class="btn btn-sm btn-warning">Selanjutnya</a>

        </div>
    </div>

    <!-- Navigasi bawah -->
    <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
    <div class="container">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <span id="answered">0</span>/<span id="totals">{{ $jumlah_soal }}</span> Soal Terjawab
            </li>
            <li class="nav-item ms-3">
                <a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial">
                    <i class="fa fa-question-circle" style="font-size: 1.5rem"></i>
                </a>
            </li>
            <li class="nav-item ms-3">
    <button type="button" class="btn btn-md btn-primary text-uppercase" id="btn-submit">
        Kumpulkan Jawaban
    </button>
</li>

        </ul>
    </div>
</nav>

<!-- Modal Tutorial -->
<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="height: 60vh">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <span class="bg-warning rounded-1 text-center px-3 py-2 me-2">
                        <i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i>
                    </span>
                    Tutorial Tes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Isi tutorial bisa ditambahkan di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-uppercase" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

    @endif
</div>

<!-- Modal Konfirmasi Submit -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="konfirmasiLabel">Konfirmasi Submit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Masih ada soal yang belum dijawab. Yakin ingin mengumpulkan sekarang?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirm-submit">Ya, Kirim Jawaban</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js-extra')
<script src="{{ asset('assets/js/quiz-render.js') }}"></script>
@endsection

@section('css-extra')
<!-- Optional: custom styling -->
<style>
    /* Kotak navigasi soal */
    .nav-soal {
        width: 40px;
        height: 30px;
        border-radius: 4px;
        font-weight: bold;
        text-align: center;
        line-height: 20px;
        cursor: pointer;
        margin: 3px;
        border: 2px solid #ccc;
        transition: all 0.2s ease-in-out;
    }

    /* Soal aktif */
    .nav-soal.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0a58ca;
    }

    /* Sudah dijawab */
    .nav-soal.answered {
        background-color: #198754;
        color: white;
        border-color: #146c43;
    }

    /* Belum dijawab */
    .nav-soal.unanswered {
        background-color: #dc3545;
        color: white;
        border-color: #b02a37;
    }

    /* Hover effect */
    .nav-soal:hover {
        transform: scale(1.1);
    }
</style>
@endsection
