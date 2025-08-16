@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        {{-- <h3>{{ $questions->packet->name }}</h3> --}}
        <h2 id="timer">00:00</h2>
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
    @if($selection != null)
        @if(strtotime('now') < strtotime($selection->test_time))
        <div class="row">
            <!-- Alert -->
            <div class="col-12 mb-2">
                <div class="alert alert-danger fade show text-center" role="alert">
                    Tes akan dilaksanakan pada tanggal <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
                </div>
            </div>
        </div>
        @endif
    @endif
    @if($selection == null || ($selection != null && strtotime('now') >= strtotime($selection->test_time)))
    <div id="questmsdt" class="row" style="margin-bottom:100px">
        <div class="col-12 col-md-4 co mb-md-0">
            <div class="card">
                <div class="card-header fw-bold text-center">Navigasi Soal</div>
                <div class="card-body">
                <form id="form" method="POST" action="{{ route('tes.submit', ['path' => $path]) }}">
    @csrf
    <input type="hidden" name="path" value="{{ $path }}">
    <input type="hidden" name="packet_id" value="{{ $packet->id }}">
    <input type="hidden" name="test_id" value="{{ $test->id }}">
    <input type="hidden" name="jumlah_soal" id="jumlah_soal" value="{{ $jumlah_soal }}">
    <input type="hidden" name="part" id="part" value="{{ $part }}">
    <div id="soal-container"></div>
</form>



                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <form id="form2">
                @csrf
                <div class="">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card soal rounded-1 mb-3">
                                <div class="soal_number card-header bg-transparent">
                                    <i class="fa fa-edit"></i> <span class="num fw-bold"></span>
                                </div>
                                <div class="card-body s" >

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a type="button" id="prev" style="display:none;font-size:1rem" class="btn btn-sm btn-warning">Sebelumnya</a>
                    <a type="button" id="next" style="font-size:1rem;float: right;" class="btn btn-sm btn-warning">Selanjutnya</a>
                </div>
            </form>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
        <div class="container">
            <ul class="navbar nav ms-auto">
                <li class="nav-item">
                    <span id="answered">0</span>/<span id="totals"></span> Soal Terjawab
                </li>
                <li class="nav-item ms-3">
                    <a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
                </li>
                <li class="nav-item ms-3">
                <!-- <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase" id="btn-nextj" disabled>Submit</button> -->

                    <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-submit" style="display: none">Submit</button>
                    <!-- <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-tiki" style="display: none">Submit</button> -->
                </li>
            </ul>
        </div>
    </nav>
    
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
    @endif
</div>

@endsection

@section('js-extra')
<script src="{{ asset('assets/js/quiz-render.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.getElementById('btn-submit');
    const confirmSubmit = document.getElementById('confirm-submit');
    const form = document.getElementById('form');

    // Klik tombol submit → buka modal
    btnSubmit.addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('konfirmasiModal')).show();
    });

    // Klik "Ya, Kirim Jawaban" → submit form
    confirmSubmit.addEventListener('click', function () {
        form.action = `/tes/{path}/submit`; // ubah action sesuai route
        form.submit();
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalTime = 15 * 60 * 1000; // 15 menit dalam ms
    const timerDisplay = document.getElementById('timer');
    const form = document.getElementById('form');

    // Ambil atau set waktu mulai
    let startTime = localStorage.getItem('quizStartTime');
    if (!startTime) {
        startTime = new Date().getTime();
        localStorage.setItem('quizStartTime', startTime);
    } else {
        startTime = parseInt(startTime);
    }

    function updateTimer() {
        const now = new Date().getTime();
        const elapsed = now - startTime;
        const remaining = totalTime - elapsed;

        if (remaining <= 0) {
            timerDisplay.innerText = "00:00";
            localStorage.removeItem('quizStartTime');
            form.submit();
            return;
        }

        const minutes = Math.floor((remaining / 1000) / 60);
        const seconds = Math.floor((remaining / 1000) % 60);
        timerDisplay.innerText =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    setInterval(updateTimer, 1000);
    updateTimer();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jumlahSoal = parseInt(document.getElementById('jumlah_soal').value);
    const btnSubmit = document.getElementById('btn-nextj');

    function updateSubmitStatus() {
        let totalTerjawab = 0;

        for (let i = 1; i <= jumlahSoal; i++) {
            const selector = `input[name="jawaban[${i}]"]:checked, input[name="jawaban[${i}][]"]:checked`;
            if (document.querySelectorAll(selector).length > 0) {
                totalTerjawab++;
            }
        }

        // Update counter
        document.getElementById('answered').innerText = totalTerjawab;

        // Enable tombol kalau semua soal terjawab
        btnSubmit.disabled = totalTerjawab < jumlahSoal;
    }

    // Jalankan saat load
    updateSubmitStatus();

    // Cek setiap kali user jawab soal
    document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function (input) {
        input.addEventListener('change', updateSubmitStatus);
    });

    // Proteksi saat submit diklik
    btnSubmit.addEventListener('click', function (e) {
        const total = parseInt(document.getElementById('answered').innerText);
        if (total < jumlahSoal) {
            e.preventDefault();
            alert(`Masih ada ${jumlahSoal - total} soal yang belum dijawab!`);
        }
    });
});
</script>
@endsection


@section('css-extra')
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
</style>
@endsection
