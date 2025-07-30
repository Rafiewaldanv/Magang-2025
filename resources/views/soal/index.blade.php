@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading" style="display: none;">
    <div class="spinner"></div>
</div>

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h2 id="timer">00:00</h2>
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
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

            <div class="d-flex justify-content-between">
                <a type="button" id="prev" class="btn btn-sm btn-warning" style="display:none; font-size:1rem">Sebelumnya</a>
                <a type="button" id="next" class="btn btn-sm btn-warning" style="font-size:1rem;">Selanjutnya</a>
            </div>
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
                    <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase" id="btn-nextj" disabled>Submit</button>
                </li>
            </ul>
        </div>
    </nav>
    @endif
</div>
@endsection

@section('js-extra')
<script src="{{ asset('assets/js/quiz-render.js') }}"></script>
@endsection

@section('css-extra')
<!-- Optional: custom styling -->
@endsection
