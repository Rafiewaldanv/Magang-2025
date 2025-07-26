@extends('template.main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>Debug Mode</h3>
        <h2 id="timer">00:00</h2>
    </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>

<div class="container main-container mt-4">
    @if($selection)
        @if(strtotime('now') < strtotime($selection->test_time))
            <div class="alert alert-danger text-center">
                Tes akan dilaksanakan pada:
                <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong>
                pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
            </div>
        @endif
    @endif

    @if(!$selection || strtotime('now') >= strtotime($selection->test_time))
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header text-center fw-bold">Navigasi Soal</div>
                <div class="card-body">
                    <form method="POST" action="/tes/{{ $path }}/store">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}">
                        <input type="hidden" name="packet_id" value="{{ $packet->id }}">
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        <input type="hidden" name="jumlah_soal" value="{{ $jumlah_soal }}">
                        <input type="hidden" name="part" value="{{ $part }}">
                        <div id="soal-container"><!-- Soal akan di-render di sini --></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <form id="form2">
                @csrf
                <div class="card soal mb-3">
                    <div class="card-header">
                        <i class="fa fa-edit"></i> <span class="num fw-bold">No. Soal</span>
                    </div>
                    <div class="card-body s">
                        <!-- Soal utama akan tampil di sini -->
                    </div>
                </div>

                <div>
                    <a id="prev" style="display:none;" class="btn btn-sm btn-warning">Sebelumnya</a>
                    <a id="next" class="btn btn-sm btn-warning float-end">Selanjutnya</a>
                </div>
            </form>
        </div>
    </div>

    <nav class="navbar navbar-light bg-white shadow fixed-bottom">
        <div class="container d-flex justify-content-between">
            <span><span id="answered">0</span>/<span id="totals">{{ $jumlah_soal }}</span> Soal Terjawab</span>
            <div>
                <a href="#" class="text-secondary me-3" data-bs-toggle="modal" data-bs-target="#tutorialModal">
                    <i class="fa fa-question-circle" style="font-size: 1.5rem;"></i>
                </a>
                <button class="btn btn-primary" onclick="deleteItems()">Submit</button>
            </div>
        </div>
    </nav>

    <!-- Modal Tutorial -->
    <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="height: 60vh">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="bg-warning px-3 py-2 me-2"><i class="fa fa-lightbulb-o text-dark"></i></span>
                        Tutorial Tes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Silakan pelajari terlebih dahulu cara mengerjakan tes.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('js-extra')
<script src="{{ asset('assets/js/quiz-render.js') }}"></script>
@endsection

@section('css-extra')
<!-- Tambahkan CSS tambahan jika diperlukan -->
@endsection
