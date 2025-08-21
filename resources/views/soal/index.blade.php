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
@php
  // definisi global untuk view: packetNameInline
  $packetNameInline = null;

  if (isset($packet) && !empty($packet->name)) {
      $packetNameInline = $packet->name;
  } elseif (session('packet_id') || session('selected_packet_id')) {
      $packetNameInline = 'Paket #' . (session('packet_id') ?? session('selected_packet_id'));
  }
@endphp
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
  <div class="card-header fw-bold text-center">
    <span> Navigasi Soal </span>
  </div>

  <div class="card-body">
    {{-- MOBILE: badge dipindah ke dalam card-body, tampil hanya di xs/sm --}}
    @if(!empty($packetNameInline))
      <div class="mb-3 d-block d-md-none">
        <div class="packet-badge-inline-mobile" title="{{ $packetNameInline }}">
          {{ $packetNameInline }}
        </div>
      </div>
    @endif

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
  {{-- kiri: packet name (desktop only) --}}
  <div class="packet-left">
    @if($packetNameInline)
      {{-- DESKTOP: tampil pada md ke atas --}}
      <div class="packet-badge-inline d-none d-md-inline-block" title="{{ $packetNameInline }}">
        {{ $packetNameInline }}
      </div>
    @endif
  </div>

  {{-- kanan: timer --}}
  <div class="text-end ms-3">
    <h2 id="timer" class="mb-0 fw-bold">15:00</h2>
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
document.addEventListener('DOMContentLoaded', function () {
  // ---------- helper CSRF ----------
  function readCsrfToken() {
    // 1) meta tag
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && meta.getAttribute('content')) return meta.getAttribute('content');

    // 2) hidden input _token (if any form on page)
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput && tokenInput.value) return tokenInput.value;

    // 3) cookie XSRF-TOKEN (Laravel sets it by default)
    const cookieMatch = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    if (cookieMatch && cookieMatch[1]) {
      try {
        // cookie is URL encoded
        return decodeURIComponent(cookieMatch[1]);
      } catch (e) {
        return cookieMatch[1];
      }
    }

    return '';
  }

  const csrf = readCsrfToken();

  // ---------- common elements ----------
  const formEl = document.getElementById('form');
  const packetInput = formEl ? formEl.querySelector('input[name="packet_id"]') : null;
  const packetId = packetInput ? packetInput.value : null;
  window.__quizStartKey = packetId ? `quizStartTime_${packetId}` : 'quizStartTime';

  // helper clear storage
  function clearQuizStorageAndTimerFor(packetIdToClear) {
    try {
      if (packetIdToClear) {
        localStorage.removeItem(`quizStartTime_${packetIdToClear}`);
        localStorage.removeItem(`quizCurrent_${packetIdToClear}`);
        localStorage.removeItem(`quizPerStart_${packetIdToClear}`);
        localStorage.removeItem(`quizPerEnd_${packetIdToClear}`);
        localStorage.removeItem(`quizPerCurrent_${packetIdToClear}`);
        localStorage.removeItem(`quizTotalStart_${packetIdToClear}`);
        localStorage.removeItem(`quizTotalDuration_${packetIdToClear}`);
      } else {
        // generic key
        localStorage.removeItem(window.__quizStartKey);
      }
    } catch (e) { console.warn('clear localStorage error', e); }
    try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
    try {
      if (window.__quizTimerInterval) {
        clearInterval(window.__quizTimerInterval);
        window.__quizTimerInterval = null;
      }
    } catch (e) {}
  }

  // ---------- form submit interception ----------
  if (formEl) {
    // prevent double-binding
    if (!formEl._hasSubmitHandler) {
      formEl.addEventListener('submit', function (e) {
        e.preventDefault();
        // kirimJawaban defined elsewhere
        if (typeof kirimJawaban === 'function') kirimJawaban();
      });
      formEl._hasSubmitHandler = true;
    }
  }

  // ---------- soal.index back/popstate handling ----------
  @if(Route::is('soal.index'))
    try {
      history.pushState(null, null, location.href);
      window.onpopstate = function (event) {
        const btnKembali = document.getElementById('btn-kembali');
        if (btnKembali) btnKembali.click();
      };
    } catch (e) { console.warn('popstate init failed', e); }
  @endif

  // ---------- modalKembali (confirm-kembali) robust handler ----------
  (function attachConfirmKembali() {
    const confirmKembaliEl = document.getElementById('confirm-kembali');
    if (!confirmKembaliEl) return;

    // prevent attaching twice
    if (confirmKembaliEl._attached) return;
    confirmKembaliEl._attached = true;

    // helper to read packet id from DOM if not available from form
    function getPacketIdFromDom() {
      const pktInput = document.querySelector('input[name="packet_id"]');
      if (pktInput && pktInput.value) return pktInput.value;
      const badge = document.getElementById('modal-packet-name') || document.querySelector('.packet-badge-inline, .packet-badge-inline-mobile');
      if (badge) {
        if (badge.dataset && badge.dataset.packetId) return badge.dataset.packetId;
        const m = (badge.textContent || '').match(/#(\d+)/);
        if (m) return m[1];
      }
      return null;
    }

    confirmKembaliEl.addEventListener('click', function (evt) {
      evt.preventDefault();
      evt.stopPropagation();

      // disable & feedback
      confirmKembaliEl.setAttribute('disabled', 'disabled');
      confirmKembaliEl.classList.add('disabled');
      const originalHTML = confirmKembaliEl.innerHTML;
      confirmKembaliEl.innerHTML = 'Memproses...';

      const pkt = getPacketIdFromDom();

      // send cancel request
      fetch("{{ route('test.cancel') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ packet_id: pkt })
      })
      .then(async resp => {
        if (!resp.ok) {
          const txt = await resp.text().catch(()=>null);
          throw new Error('HTTP ' + resp.status + (txt ? ': '+txt : ''));
        }
        return resp.json().catch(()=>({ ok:true }));
      })
      .then(data => {
        // cleanup client-side
        try { clearQuizStorageAndTimerFor(pkt); } catch(e){}

        // hide modalKembali if open
        try {
          const modalEl = document.getElementById('modalKembali');
          if (modalEl) {
            const bs = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            bs.hide();
          }
        } catch(e){}

        // redirect to href (default '/')
        const href = confirmKembaliEl.getAttribute('href') || '/';
        window.location.href = href;
      })
      .catch(err => {
        console.error('Cancel request failed:', err);
        // restore button
        confirmKembaliEl.removeAttribute('disabled');
        confirmKembaliEl.classList.remove('disabled');
        confirmKembaliEl.innerHTML = originalHTML;
        alert('Gagal membatalkan tes. Silakan coba lagi.');
      });
    });
  })();

  // ---------- optional: attach modalKembali popstate protection ----------
  (function attachPopstateModalKembali() {
    const modalKembaliEl = document.getElementById('modalKembali');
    if (!modalKembaliEl) return;
    // prevent duplicate instance creation
    if (!modalKembaliEl._popstateAttached) {
      modalKembaliEl._popstateAttached = true;
      try {
        window.history.pushState(null, '', window.location.href);
        window.addEventListener('popstate', function (e) {
          // show modal only if not open
          if (!document.body.classList.contains('modal-open')) {
            const bs = new bootstrap.Modal(modalKembaliEl, { backdrop: true });
            bs.show();
          }
          window.history.pushState(null, '', window.location.href);
        });
      } catch (err) {
        console.warn('popstate not available', err);
      }

      // cleanup leftover backdrops when modal closed
      modalKembaliEl.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(node => node.remove());
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
      });
    }
  })();

  // ---------- submit confirm modal handler ----------
  (function attachSubmitConfirm() {
    const btnSubmit = document.getElementById('btn-submit');
    const confirmSubmit = document.getElementById('confirm-submit');

    if (btnSubmit && !btnSubmit._attached) {
      btnSubmit._attached = true;
      btnSubmit.addEventListener('click', function (e) {
        e.preventDefault();
        const konf = document.getElementById('konfirmasiModal');
        if (konf) new bootstrap.Modal(konf).show();
      });
    }

    if (confirmSubmit && !confirmSubmit._attached) {
      confirmSubmit._attached = true;
      confirmSubmit.addEventListener('click', function (e) {
        // clear and then submit form
        try { clearQuizStorageAndTimerFor(packetId); } catch(e){}
        if (formEl) {
          setTimeout(() => formEl.submit(), 150);
        }
      });
    }

    // also ensure normal form submit clears storage (catch all)
    if (formEl && !formEl._clearAttached) {
      formEl._clearAttached = true;
      formEl.addEventListener('submit', function () {
        try { clearQuizStorageAndTimerFor(packetId); } catch(e){}
      });
    }
  })();

  // ---------- answered counter logic (kept intact) ----------
  (function attachAnsweredCounter() {
    const jumlahSoalEl = document.getElementById('jumlah_soal');
    if (!jumlahSoalEl) return;
    const jumlahSoal = parseInt(jumlahSoalEl.value);
    const btnNextj = document.getElementById('btn-nextj');

    function updateSubmitStatus() {
      let totalTerjawab = 0;
      for (let i = 1; i <= jumlahSoal; i++) {
        const selector = `input[name="jawaban[${i}]"]:checked, input[name="jawaban[${i}][]"]:checked`;
        if (document.querySelectorAll(selector).length > 0) totalTerjawab++;
      }
      const answeredEl = document.getElementById('answered');
      if (answeredEl) answeredEl.innerText = totalTerjawab;
      if (btnNextj) btnNextj.disabled = totalTerjawab < jumlahSoal;
    }

    updateSubmitStatus();
    document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function (input) {
      input.addEventListener('change', updateSubmitStatus);
    });

    if (btnNextj && !btnNextj._protectAttached) {
      btnNextj._protectAttached = true;
      btnNextj.addEventListener('click', function (e) {
        const total = parseInt(document.getElementById('answered').innerText || '0');
        if (total < jumlahSoal) {
          e.preventDefault();
          alert(`Masih ada ${jumlahSoal - total} soal yang belum dijawab!`);
        }
      });
    }
  })();

}); // end DOMContentLoaded
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
