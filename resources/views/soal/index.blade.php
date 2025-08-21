@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <!-- restored timer display (handled by quiz-render.js) -->
        <h2 id="timesr">00:00</h2>
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>


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
    <div id="mobile-nav-placeholder"></div>
    <div id="questmsdt" class="row" style="margin-bottom:100px">
        <div class="col-12 col-md-4 co mb-md-0">
            <div class="card">
                <div class="card-header fw-bold text-center">Navigasi Soal</div>
                <div class="card-body">
                    <form id="form" method="POST" action="{{ route('soal.simpan', ['path' => $path]) }}">
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

        <div class="col-12 col-md-8">
            <form id="form2">
                @csrf
                <div class="">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card soal rounded-1 mb-3">
                                {{-- restored timer in card header (quiz-render.js controls behavior) --}}
                                @php
  // cari nama paket: prioritas dari $packet->name, lalu session packet_id/selected_packet_id
  $packetNameInline = null;
  if (isset($packet) && !empty($packet->name)) {
    $packetNameInline = $packet->name;
  } elseif (session('packet_id') || session('selected_packet_id')) {
    $packetNameInline = 'Paket #' . (session('packet_id') ?? session('selected_packet_id'));
  }
@endphp

<div class="card-header bg-transparent d-flex align-items-center justify-content-between">
  {{-- kiri: packet name (jika ada) --}}
  <div class="packet-left">
    @if($packetNameInline)
      <div class="packet-badge-inline" title="{{ $packetNameInline }}">{{ $packetNameInline }}</div>
    @endif
  </div>

  {{-- kanan: timer --}}
  <div class="text-end ms-3">
    <h2 id="timer" class="mb-0 fw-bold">30:00</h2>
  </div>
</div>


                                <div class="soal_number card-header bg-transparent">
                                    <i class="fa fa-edit"></i> <span class="num fw-bold"></span>
                                </div>
                                <div class="card-body s"></div>
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
                    <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-submit" style="display: none">Submit</button>
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
                
                <div class="modal-body text-start overflow-auto">
                    <h6 class="fw-bold">Panduan Mengerjakan Tes:</h6>
                    <ol class="mt-2">
                        <li>Baca setiap soal dengan seksama sebelum menjawab.</li>
                        <li>Pilih salah satu jawaban dengan mengklik opsi yang tersedia.</li>
                        <li>Jika soal berupa gambar, perhatikan detail gambar dengan baik.</li>
                        <li>Gunakan tombol <b>Next</b> untuk maju ke soal berikutnya dan <b>Previous</b> untuk kembali ke soal sebelumnya.</li>
                        <li>Pastikan semua soal sudah dijawab. Sistem tidak akan mengizinkan submit jika ada soal yang kosong.</li>
                        <li>Kamu bisa mengganti jawaban kapan saja sebelum menekan tombol <b>Submit</b>.</li>
                        <li>Setelah menekan tombol <b>Submit</b>, jawaban akan disimpan dan <b>tidak dapat diubah kembali</b>.</li>
                    </ol>
                    
                    <div class="alert alert-info mt-3" role="alert">
                        ℹ️ Tips: Kerjakan soal yang mudah terlebih dahulu agar lebih efisien.
                    </div>
                    
                    <div class="alert alert-warning mt-2" role="alert">
                        ⚠️ Waktu pengerjaan terbatas, pastikan memanfaatkan waktu dengan baik.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary text-uppercase" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKembali" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Keluar Tes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            Jika keluar, tes dianggap <strong>dibatalkan</strong> dan tidak bisa dilanjutkan lagi. 
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
document.addEventListener("DOMContentLoaded", function () {
    // Deteksi apakah kita di halaman soal.index
    @if(Route::is('soal.index'))
        // Dorong state baru biar tombol back gak langsung balik ke home
        history.pushState(null, null, location.href);

        window.onpopstate = function (event) {
            // Saat tombol back ditekan, trigger tombol kembali custom
            const btnKembali = document.getElementById('btn-kembali');
            if (btnKembali) btnKembali.click();
        };
    @endif
});
</script>

<script>
document.getElementById("form")?.addEventListener("submit", function(e) {
    e.preventDefault(); // cegah langsung submit
    kirimJawaban();     // pastikan isi jawaban ditambahkan dulu
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // --- Shared setup: dedup & key discovery ---
  const $form = document.getElementById('form');
  const packetInput = $form ? $form.querySelector('input[name="packet_id"]') : null;
  const packetId = packetInput ? packetInput.value : null;
  // key unik per packet; fallback ke generic supaya tidak error bila packetId null
  const START_KEY = packetId ? `quizStartTime_${packetId}` : 'quizStartTime';
  window.__quizStartKey = START_KEY;

  // helper clear timer & related storage (meskipun timer dihapus, fungsi ini tetap berguna untuk membersihkan storage)
  function clearQuizStorageAndTimer() {
    try {
      localStorage.removeItem(window.__quizStartKey);
    } catch (e) { console.warn('clear localStorage error', e); }
    try {
      sessionStorage.removeItem('jawabanSementara');
    } catch (e) {}
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
      // biarkan href berjalan
    });
  }

  // Intercept browser back (popstate) untuk menampilkan modal juga
  try {
    window.history.pushState(null, '', window.location.href);
    window.addEventListener('popstate', function (e) {
      if (modalKembaliEl) {
        new bootstrap.Modal(modalKembaliEl).show();
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
      if ($form) {
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

  // NOTE: Timer-related logic removed as requested.
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jumlahSoalEl = document.getElementById('jumlah_soal');
    if (!jumlahSoalEl) return;
    const jumlahSoal = parseInt(jumlahSoalEl.value);
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
        const answeredEl = document.getElementById('answered');
        if (answeredEl) answeredEl.innerText = totalTerjawab;

        // Enable tombol kalau semua soal terjawab
        if (btnSubmit) btnSubmit.disabled = totalTerjawab < jumlahSoal;
    }

    // Jalankan saat load
    updateSubmitStatus();

    // Cek setiap kali user jawab soal
    document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function (input) {
        input.addEventListener('change', updateSubmitStatus);
    });

    // Proteksi saat submit diklik
    if (btnSubmit) {
      btnSubmit.addEventListener('click', function (e) {
          const total = parseInt(document.getElementById('answered').innerText || '0');
          if (total < jumlahSoal) {
              e.preventDefault();
              alert(`Masih ada ${jumlahSoal - total} soal yang belum dijawab!`);
          }
      });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalKembaliEl = document.getElementById('modalKembali');
  // jika modal ada, buat satu instance dan simpan
  const modalKembali = modalKembaliEl ? new bootstrap.Modal(modalKembaliEl, { backdrop: true }) : null;
  const confirmKembali = document.getElementById('confirm-kembali');

  // handler tombol 'Keluar' (konfirmasi)
  if (confirmKembali) {
    confirmKembali.addEventListener('click', function () {
      // bersihkan storage jika perlu
      try { localStorage.removeItem(window.__quizStartKey); } catch(e){}
      try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
      // biarkan <a href="/"> menjalankan redirect
    });
  }

  // Intercept browser back: show modal hanya jika belum terbuka
  try {
    // pastikan ada satu pushState supaya popstate dipicu
    window.history.pushState(null, '', window.location.href);

    window.addEventListener('popstate', function (e) {
      if (!modalKembali) return;
      // jika modal belum terbuka, tampilkan. jika sudah terbuka, jangan show lagi.
      if (!document.body.classList.contains('modal-open')) {
        modalKembali.show();
      }
      // kembali dorong state agar user tidak langsung navigasi away
      // (ini mencegah back action, tapi jangan lakukan push berkali-kali terus menerus)
      window.history.pushState(null, '', window.location.href);
    });
  } catch (err) {
    console.warn('popstate not available', err);
  }

  // Pastikan saat modal tertutup, semua backdrop & class dibersihkan
  if (modalKembaliEl) {
    modalKembaliEl.addEventListener('hidden.bs.modal', function () {
      // hapus semua backdrop sisa jika ada
      document.querySelectorAll('.modal-backdrop').forEach(node => node.remove());
      // hapus kelas modal-open pada body (jika masih ada)
      document.body.classList.remove('modal-open');
      // (opsional) restore scroll
      document.body.style.paddingRight = '';
    });
  }
});

</script>
@endsection

@section('css-extra')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

/* Kotak navigasi soal */
</style>
@endsection
