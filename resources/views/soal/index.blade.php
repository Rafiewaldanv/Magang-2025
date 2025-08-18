@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        {{-- <h3>{{ $questions->packet->name }}</h3> --}}
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
    {{-- Timer di atas tulisan "Soal Nomor" --}}
    <div class="card-header bg-transparent text-end">
        <h2 id="timer" class="mb-0 fw-bold">30:00</h2>
    </div>

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
    <div class="modal fade" id="modalKembali" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Keluar Tes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Jika keluar, tes dianggap <strong>selesai</strong> dan tidak bisa dilanjutkan lagi. 
        Yakin ingin keluar?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="/" class="btn btn-danger" id="confirm-kembali">Ya, Keluar</a>
      </div>
    </div>
  </div>
</div>

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
  // --- Shared setup: dedup & key discovery ---
  const $form = document.getElementById('form');
  const packetInput = $form ? $form.querySelector('input[name="packet_id"]') : null;
  const packetId = packetInput ? packetInput.value : null;
  // key unik per packet; fallback ke generic supaya tidak error bila packetId null
  const START_KEY = packetId ? `quizStartTime_${packetId}` : 'quizStartTime';
  // simpan di global supaya handler lain bisa akses
  window.__quizStartKey = START_KEY;

  // helper clear timer & related storage
  function clearQuizStorageAndTimer() {
    try {
      localStorage.removeItem(window.__quizStartKey);
    } catch (e) { console.warn('clear localStorage error', e); }
    try {
      sessionStorage.removeItem('jawabanSementara');
    } catch (e) {}
    // stop interval jika dijalankan
    try {
      if (window.__quizTimerInterval) {
        clearInterval(window.__quizTimerInterval);
        window.__quizTimerInterval = null;
      }
    } catch (e) {}
  }

  // --- Navbar back override + modal handling ---
  const btnKembali = document.getElementById('btn-kembali');
  const modalKembaliEl = document.getElementById('modalKembali');
  const confirmKembali = document.getElementById('confirm-kembali');

  if (btnKembali) {
    btnKembali.addEventListener('click', function (e) {
      e.preventDefault();
      if (modalKembaliEl) new bootstrap.Modal(modalKembaliEl).show();
    });
  }

  if (confirmKembali) {
    confirmKembali.addEventListener('click', function (e) {
      // hapus storage & hentikan timer, lalu pindah halaman
      clearQuizStorageAndTimer();
      // biarkan href berjalan — kalau itu anchor, window.location.href akan diikuti
      // jika ingin memastikan redirect, uncomment baris berikut:
      // window.location.href = confirmKembali.getAttribute('href') || '/';
    });
  }

  // Intercept browser back (popstate) untuk menampilkan modal juga
  try {
    // push state agar popstate terjadi saat user tekan back
    window.history.pushState(null, '', window.location.href);
    window.addEventListener('popstate', function (e) {
      if (modalKembaliEl) {
        new bootstrap.Modal(modalKembaliEl).show();
        // dorong state kembali agar user tidak langsung navigate away
        window.history.pushState(null, '', window.location.href);
      }
    });
  } catch (e) {
    console.warn('popstate not available', e);
  }

  // --- Submit handlers (navbar submit and confirm) ---
  const btnSubmit = document.getElementById('btn-submit');
  const confirmSubmit = document.getElementById('confirm-submit');

  if (btnSubmit) {
    btnSubmit.addEventListener('click', function (e) {
      e.preventDefault();
      const konf = document.getElementById('konfirmasiModal');
      if (konf) new bootstrap.Modal(konf).show();
    });
  }

  if (confirmSubmit) {
    confirmSubmit.addEventListener('click', function (e) {
      // clear and then submit form
      clearQuizStorageAndTimer();
      // submit the form (form action is already set to your tes.submit route)
      if ($form) {
        // make sure form is submitted after modal is hidden (small delay)
        setTimeout(() => $form.submit(), 150);
      }
    });
  }

  // also ensure normal form submit clears storage (catch all)
  if ($form) {
    $form.addEventListener('submit', function () {
      clearQuizStorageAndTimer();
    });
  }

  // --- TIMER (hanya aktif kalau route soal, atau jika #timer ada) ---
  const timerEl = document.getElementById('timer');
  if (!timerEl) {
    // no timer on this page — nothing to do further
    return;
  }

  const TOTAL_MS = 15 * 60 * 1000; // 15 menit (ubah ke 30*60*1000 kalau mau 30 menit)
  // gunakan startTime stored, kalau belum ada set sekarang
  let startTime = localStorage.getItem(window.__quizStartKey);
  if (startTime) {
    startTime = parseInt(startTime, 10);
  } else {
    startTime = Date.now();
    try { localStorage.setItem(window.__quizStartKey, startTime); } catch (e) {}
  }

  // prevent double init (jika script dieksekusi dua kali)
  if (window.__quizTimerInterval) {
    // sudah berjalan — update timer immediately and exit
    (function immediateUpdate() {
      const now = Date.now();
      const elapsed = now - startTime;
      const remaining = TOTAL_MS - elapsed;
      if (remaining <= 0) {
        timerEl.innerText = '00:00';
      } else {
        const m = Math.floor((remaining / 1000) / 60);
        const s = Math.floor((remaining / 1000) % 60);
        timerEl.innerText = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
      }
    })();
    return;
  }

  function updateTimerOnce() {
    const now = Date.now();
    const elapsed = now - startTime;
    const remaining = TOTAL_MS - elapsed;

    if (remaining <= 0) {
      timerEl.innerText = '00:00';
      // auto-submit: make sure we only submit once
      clearQuizStorageAndTimer();
      // trigger form submit if available
      if ($form) {
        // inject answers from sessionStorage (if you use that) — optional
        const answers = JSON.parse(sessionStorage.getItem('jawabanSementara') || '{}');
        // remove previous dynamic inputs
        $form.querySelectorAll('input._auto_ans').forEach(n => n.remove());
        for (const num in answers) {
          if (!answers.hasOwnProperty(num)) continue;
          const val = Array.isArray(answers[num]) ? answers[num].join(',') : (answers[num] ?? '');
          const inp = document.createElement('input');
          inp.type = 'hidden';
          inp.name = `answers[${num}]`;
          inp.value = String(val);
          inp.className = '_auto_ans';
          $form.appendChild(inp);
        }
        // optional time_taken
        const timeTakenSec = Math.round(elapsed / 1000);
        const tt = document.createElement('input');
        tt.type = 'hidden';
        tt.name = 'time_taken';
        tt.value = String(timeTakenSec);
        tt.className = '_auto_ans';
        $form.appendChild(tt);

        // submit
        setTimeout(() => $form.submit(), 100);
      }
      // stop interval
      if (window.__quizTimerInterval) {
        clearInterval(window.__quizTimerInterval);
        window.__quizTimerInterval = null;
      }
      return;
    }

    const minutes = Math.floor((remaining / 1000) / 60);
    const seconds = Math.floor((remaining / 1000) % 60);
    timerEl.innerText = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
  }

  // start interval and store id globally so confirmKembali can clear it
  updateTimerOnce();
  window.__quizTimerInterval = setInterval(updateTimerOnce, 1000);

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
.q-img {
  display: inline-block;
  width: 160px;
  height: 160px;
  object-fit: cover;
  border-radius: 1px;
  margin-right: 8px;
  margin-bottom: 6px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.q-image-row {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 8px;
  overflow-x: auto;
  padding: 6px 0;
  white-space: nowrap;
}
.list-group-item .q-img { margin-right: 10px; }
</style>
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
