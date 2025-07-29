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
                    {{-- <form id="form" method="post" action="/tes/{{ $path }}/store"> --}}
                    <form id="form" method="post" action="/tes/{{ $path }}/store">
                        @csrf
                    
                        <input type="hidden" name="path" value="{{ $path }}">
                        <input type="hidden" name="packet_id" value="{{ $packet->id }}">
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        <input type="hidden" name="jumlah_soal" class="jumlah_soal" id="jumlah_soal" value="{{ $jumlah_soal }}">
                        <input type="hidden" name="part" class="part" id="part" value="{{ $part }}">
                        <div id="soal-container"></div>


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
                <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase" id="btn-nextj" disabled>Submit</button>

                    <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-submit" style="display: none">Submit</button>
                    <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-tiki" style="display: none">Submit</button>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="height: 60vh">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span class="bg-warning rounded-1 text-center px-3 py-2 me-2"><i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i></span>
                        Tutorial Tes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">Mengerti</button>
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
    const jumlahSoal = parseInt(document.getElementById('jumlah_soal').value);
    const btnSubmit = document.getElementById('btn-nextj');
    const soalBody = document.querySelector('.card-body.s');
    const soalNumber = document.querySelector('.soal_number .num');
    const formNavigasi = document.getElementById('form');
    const containerNav = document.getElementById('soal-container');
  const testID = {{ $test->id }};
  const packetID = {{ $packet->id }};





    let current = 1;
    let soalData = {};

    // Ambil soal via AJAX saat halaman siap
    fetch(`/api/soal/${testID}/${packetID}/${soalNumber}`)
        .then(response => response.json())
        .then(data => {
            soalData = data;
            renderNavigasi();
            tampilkanSoal(current);
            updateSubmitStatus();
        });

    function renderNavigasi() {
        for (let i = 1; i <= jumlahSoal; i++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-sm m-1 btn-outline-primary btn-nav';
            button.innerText = i;
            button.dataset.index = i;
            button.addEventListener('click', () => {
                current = i;
                tampilkanSoal(current);
            });
            containerNav.appendChild(button);
        }
    }

    function tampilkanSoal(index) {
        const soal = soalData[index];
        soalNumber.innerText = `Soal ${index}`;
        soalBody.innerHTML = `
            <p>${soal.question.text}</p>
            ${soal.options.map(opt => `
                <div class="form-check">
                    <input class="form-check-input" type="${soal.question.type === 'multiple' ? 'checkbox' : 'radio'}"
                        name="jawaban[${index}]${soal.question.type === 'multiple' ? '[]' : ''}"
                        value="${opt.id}" id="soal_${index}_${opt.id}">
                    <label class="form-check-label" for="soal_${index}_${opt.id}">
                        ${opt.text}
                    </label>
                </div>
            `).join('')}
        `;

        // Tambahkan event listener ulang
        document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function (input) {
            input.addEventListener('change', updateSubmitStatus);
        });

        updateNavigationButtons();
        highlightCurrentNav(index);
    }

    function updateNavigationButtons() {
        document.getElementById('prev').style.display = current > 1 ? 'inline-block' : 'none';
        document.getElementById('next').style.display = current < jumlahSoal ? 'inline-block' : 'none';
    }

    function highlightCurrentNav(index) {
        document.querySelectorAll('.btn-nav').forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        });
        document.querySelector(`.btn-nav[data-index="${index}"]`).classList.remove('btn-outline-primary');
        document.querySelector(`.btn-nav[data-index="${index}"]`).classList.add('btn-primary');
    }

    function updateSubmitStatus() {
        let totalTerjawab = 0;
        for (let i = 1; i <= jumlahSoal; i++) {
            const selector = `input[name="jawaban[${i}]"]:checked, input[name="jawaban[${i}][]"]:checked`;
            if (document.querySelectorAll(selector).length > 0) totalTerjawab++;
        }

        document.getElementById('answered').innerText = totalTerjawab;
        btnSubmit.disabled = totalTerjawab < jumlahSoal;

        // Tambahkan indikator ke tombol navigasi
        for (let i = 1; i <= jumlahSoal; i++) {
            const btn = document.querySelector(`.btn-nav[data-index="${i}"]`);
            const answered = document.querySelectorAll(`input[name="jawaban[${i}]"]:checked, input[name="jawaban[${i}][]"]:checked`).length > 0;
            if (answered) {
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');
            }
        }
    }

    document.getElementById('next').addEventListener('click', function () {
        if (current < jumlahSoal) {
            current++;
            tampilkanSoal(current);
        }
    });

    document.getElementById('prev').addEventListener('click', function () {
        if (current > 1) {
            current--;
            tampilkanSoal(current);
        }
    });

    btnSubmit.addEventListener('click', function (e) {
        const total = parseInt(document.getElementById('answered').innerText);
        if (total < jumlahSoal) {
            e.preventDefault();
            alert(`Masih ada ${jumlahSoal - total} soal yang belum dijawab!`);
        } else {
            formNavigasi.submit();
        }
    });
});
</script>

@endsection


@section('css-extra')

</style>
@endsection
