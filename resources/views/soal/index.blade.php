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
  <button type="button"
          class="btn btn-link text-secondary p-0"
          data-bs-toggle="modal"
          data-bs-target="#tutorialModal"
          data-suppress-modal-kembali="1"
          aria-label="Tutorial">
    <i class="fa fa-question-circle" style="font-size: 1.5rem"></i>
  </button>
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
  // ---------- CSRF helper ----------
  function readCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && meta.getAttribute('content')) return meta.getAttribute('content');
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput && tokenInput.value) return tokenInput.value;
    const cookieMatch = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    if (cookieMatch && cookieMatch[1]) {
      try { return decodeURIComponent(cookieMatch[1]); } catch (e) { return cookieMatch[1]; }
    }
    return '';
  }
  const csrf = readCsrfToken();

  // ---------- push custom quiz state so we can identify our popstate events ----------
  try {
    window.history.replaceState({ source: 'quiz' }, '', window.location.href);
    window.history.pushState({ source: 'quiz' }, '', window.location.href);
  } catch (e) {
    // ignore if not supported
  }

  // ---------- common elements & keys ----------
  const formEl = document.getElementById('form');
  const packetInput = formEl ? formEl.querySelector('input[name="packet_id"]') : null;
  const packetId = packetInput ? packetInput.value : null;
  window.__quizStartKey = packetId ? `quizStartTime_${packetId}` : 'quizStartTime';

  // ---------- utility: clear local/session storage & timer ----------
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

  // ---------- form submit interception (call kirimJawaban once) ----------
  if (formEl && !formEl._hasSubmitHandler) {
    formEl.addEventListener('submit', function (e) {
      e.preventDefault();
      if (typeof kirimJawaban === 'function') kirimJawaban();
    });
    formEl._hasSubmitHandler = true;
  }

  // ---------- keep legacy soal.index back behaviour if needed ----------
  @if(Route::is('soal.index'))
  try {
    // ensure we have our quiz states; legacy onpopstate triggers btn-kembali click
    history.pushState({ source: 'quiz' }, '', location.href);
    window.onpopstate = function (event) {
      // only respond to our quiz-state popstates
      if (event && event.state && event.state.source === 'quiz') {
        const btnKembali = document.getElementById('btn-kembali');
        if (btnKembali) btnKembali.click();
      }
    };
  } catch (e) { console.warn('popstate init failed', e); }
  @endif

  // ---------- modalKembali instance ----------
  const modalKembaliEl = document.getElementById('modalKembali');
  const modalKembaliInstance = modalKembaliEl ? (bootstrap.Modal.getInstance(modalKembaliEl) || new bootstrap.Modal(modalKembaliEl, { backdrop: true })) : null;
  const confirmKembaliBtn = document.getElementById('confirm-kembali');

  // Quick cleanup on confirmKembali click (defensive)
  if (confirmKembaliBtn && !confirmKembaliBtn._simpleCleanupAttached) {
    confirmKembaliBtn.addEventListener('click', function () {
      try { localStorage.removeItem(window.__quizStartKey); } catch(e){}
      try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
    });
    confirmKembaliBtn._simpleCleanupAttached = true;
  }

  // ---------- robust cancel (fetch) for confirm-kembali ----------
  (function attachConfirmCancelFetch() {
    if (!confirmKembaliBtn) return;
    if (confirmKembaliBtn._attachedFetch) return;
    confirmKembaliBtn._attachedFetch = true;

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

    confirmKembaliBtn.addEventListener('click', function (ev) {
      // prevent immediate navigation (if anchor) so we call server cancel first
      ev.preventDefault();
      ev.stopPropagation();

      confirmKembaliBtn.setAttribute('disabled', 'disabled');
      confirmKembaliBtn.classList.add('disabled');
      const originalHTML = confirmKembaliBtn.innerHTML;
      confirmKembaliBtn.innerHTML = 'Memproses...';

      const pkt = getPacketIdFromDom();

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
        try { clearQuizStorageAndTimerFor(pkt); } catch(e){}
        try {
          if (modalKembaliEl) {
            const bs = bootstrap.Modal.getInstance(modalKembaliEl) || new bootstrap.Modal(modalKembaliEl);
            bs.hide();
          }
        } catch(e){}
        const href = confirmKembaliBtn.getAttribute('href') || '/';
        window.location.href = href;
      })
      .catch(err => {
        console.error('Cancel request failed:', err);
        confirmKembaliBtn.removeAttribute('disabled');
        confirmKembaliBtn.classList.remove('disabled');
        confirmKembaliBtn.innerHTML = originalHTML;
        alert('Gagal membatalkan tes. Silakan coba lagi.');
      });
    });
  })();

  // ---------- suppression mechanism (capture click) to avoid race with other modals ----------
  (function attachSuppressionAndPopstate() {
    if (!modalKembaliEl) return;
    if (modalKembaliEl._suppressionAttached) return;
    modalKembaliEl._suppressionAttached = true;

    window.__suppressModalKembali = false;
    let __suppressTimer = null;
    function suppressTemporary(ms = 1200) {
      window.__suppressModalKembali = true;
      if (__suppressTimer) clearTimeout(__suppressTimer);
      __suppressTimer = setTimeout(() => {
        window.__suppressModalKembali = false;
        __suppressTimer = null;
      }, ms);
    }

    // capture phase: detect clicks that will open other modals and suppress modalKembali briefly
    document.addEventListener('click', function (ev) {
      try {
        const opener = ev.target.closest ? ev.target.closest('[data-bs-toggle="modal"], [data-suppress-modal-kembali]') : null;
        if (!opener) return;
        const target = opener.getAttribute('data-bs-target') || opener.getAttribute('href') || '';
        if (target && target.indexOf('#modalKembali') !== -1) return;
        // if opener explicitly requests longer suppression, give it
        if (opener.hasAttribute('data-suppress-modal-kembali') || opener.dataset.suppressModalKembali) {
          suppressTemporary(2000);
          return;
        }
        suppressTemporary(1200);
      } catch (err) { /* ignore */ }
    }, true);

    // bootstrap modal lifecycle listeners — keep suppression accurate
    document.querySelectorAll('.modal').forEach(function (mEl) {
      mEl.addEventListener('show.bs.modal', function () {
        window.__suppressModalKembali = true;
      });
      mEl.addEventListener('shown.bs.modal', function () {
        window.__suppressModalKembali = true;
      });
      mEl.addEventListener('hidden.bs.modal', function () {
        window.__suppressModalKembali = false;
        // cleanup stray backdrop/class
        document.querySelectorAll('.modal-backdrop').forEach(node => node.remove());
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
      });
    });

    // popstate handler — only respond to our custom quiz states and when suppression not active
    try {
      // ensure we have at least one pushed state earlier
      try { window.history.pushState({ source: 'quiz' }, '', window.location.href); } catch (e) {}

      window.addEventListener('popstate', function (e) {
        // ONLY react if this popstate came from our quiz history
        if (!e.state || e.state.source !== 'quiz') {
          // not our quiz state — ignore
          return;
        }

        // if suppression active or any other modal open, restore state and ignore
        if (window.__suppressModalKembali || document.querySelector('.modal.show') || document.body.classList.contains('modal-open')) {
          try { window.history.pushState({ source: 'quiz' }, '', window.location.href); } catch (err) {}
          return;
        }

        // otherwise show modalKembali
        try {
          if (modalKembaliInstance) modalKembaliInstance.show();
        } catch (err) {
          console.warn('Could not show modalKembali', err);
        }

        // push state back to prevent leaving
        try { window.history.pushState({ source: 'quiz' }, '', window.location.href); } catch (err) {}
      });
    } catch (err) {
      console.warn('popstate init failed', err);
    }
  })();

  // ---------- cleanup when modalKembali hidden (defensive) ----------
  if (modalKembaliEl) {
    modalKembaliEl.addEventListener('hidden.bs.modal', function () {
      document.querySelectorAll('.modal-backdrop').forEach(node => node.remove());
      document.body.classList.remove('modal-open');
      document.body.style.paddingRight = '';
    });
  }

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
        try { clearQuizStorageAndTimerFor(packetId); } catch(e){}
        if (formEl) {
          setTimeout(() => formEl.submit(), 150);
        }
      });
    }

    if (formEl && !formEl._clearAttached) {
      formEl._clearAttached = true;
      formEl.addEventListener('submit', function () {
        try { clearQuizStorageAndTimerFor(packetId); } catch(e){}
      });
    }
  })();

  // ---------- answered counter logic ----------
  (function attachAnsweredCounter() {
    const jumlahSoalEl = document.getElementById('jumlah_soal');
    if (!jumlahSoalEl) return;
    const jumlahSoal = parseInt(jumlahSoalEl.value) || 0;
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
