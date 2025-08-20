@extends('template.main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<!-- Header -->
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

<!-- Isi Halaman -->
<div class="container main-container text-center mt-5 mb-5">
    <div class="card shadow mx-auto" style="max-width: 600px">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Mulai Tes</h4>
            <p class="mb-4">Pastikan kamu sudah siap dan koneksi internet stabil sebelum memulai.</p>
            <button class="btn btn-primary text-uppercase mt-3 px-4 py-2" 
                    data-bs-toggle="modal" data-bs-target="#tutorialModal">
                Mulai Tes Sekarang
            </button>
        </div>
    </div>
</div>

<!-- Modal 1: Tutorial -->
<div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="height: 60vh">
            <div class="modal-header">
                <h5 class="modal-title" id="tutorialLabel">
                    <span class="bg-warning rounded-1 text-center px-3 py-2 me-2">
                        <i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i>
                    </span>
                    Tutorial Tes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Baca instruksi tes dengan seksama sebelum memulai.</p>
                <ul>
                    <li>Kerjakan dengan tenang.</li>
                    <li>Pastikan koneksi internet stabil.</li>
                    <li>Gunakan waktu sebaik mungkin.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-target="#confirmModal" data-bs-toggle="modal">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
<!-- {-- Modal Ongoing Test (muncul otomatis jika ada ongoingTest) --}} -->
@if(!empty($ongoingTest))
<div class="modal fade" id="modalOngoingTest" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tes Sedang Berlangsung</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Terdeteksi ada tes yang sedang berjalan. Ingin melanjutkan atau membatalkan tes?</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn-cancel-test" class="btn btn-secondary">Tidak, Batalkan</button>

        {{-- Tombol Lanjutkan: gunakan route atau URL sesuai aplikasi kamu --}}
        <a href="#" id="btn-continue-test" class="btn btn-primary">Lanjutkan Tes</a>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Modal 2: Konfirmasi -->
<!-- Modal 2: Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Pilih jenis tes yang akan kamu kerjakan:</p>

                <form id="startTestForm" method="POST" action="{{ route('soal.start') }}">
    @csrf
    <select class="form-select mb-3" name="packet_id" required>
        <option value="">-- Pilih Paket Tes --</option>
        @foreach($packets as $p)
            <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-success">Lanjutkan Tes</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Belum Siap</button>
</form>



            </div>
        </div>
    </div>
</div>
{{-- ---------- Ongoing / Welcome modals (either one rendered) ---------- --}}
@if(!empty($ongoingTest))
  {{-- Modal: Tes Sedang Berlangsung --}}
  <div class="modal fade" id="modalOngoingTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tes Sedang Berlangsung</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <p>Terdeteksi ada tes yang sedang berjalan. Ingin melanjutkan atau membatalkan tes?</p>
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-cancel-test" class="btn btn-secondary">Tidak, Batalkan</button>
          <a href="#" id="btn-continue-test" class="btn btn-primary">Lanjutkan Tes</a>
        </div>
      </div>
    </div>
  </div>
@else
  {{-- Modal: Welcome --}}
  <div class="modal fade" id="modalWelcomeTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Selamat Datang di Tes Psikologanda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-start">
          <p>Selamat datang! Sebelum mulai, baca tutorial singkat agar lebih siap mengerjakan.</p>
          <ul>
            <li>Siapkan koneksi internet yang stabil.</li>
            <li>Siapkan waktu sesuai durasi tes.</li>
            <li>Klik <strong>Mulai Tes</strong> untuk melihat tutorial dan memilih paket.</li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-welcome-later" class="btn btn-secondary" data-bs-dismiss="modal">Nanti Saja</button>
          <button type="button" id="btn-welcome-start" class="btn btn-primary">Mulai Tes</button>
        </div>
      </div>
    </div>
  </div>
@endif

{{-- ---------- Single JS section for both modals ---------- --}}
@section('js-extra')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
  // server data
  const ongoingTest = @json($ongoingTest ?? null);

  // determine which modal to show
  const hasOngoing = ongoingTest && ongoingTest.packet_id;

  // helper: show modal when bootstrap ready (retry a few times)
  function showModalWhenReady(modalId, onShow) {
    let tries = 12;
    const delay = 150;
    (function attempt() {
      if (typeof bootstrap !== 'undefined' && document.getElementById(modalId)) {
        try {
          const modalEl = document.getElementById(modalId);
          const bsModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
          bsModal.show();
          if (typeof onShow === 'function') onShow(bsModal);
          return;
        } catch (err) {
          console.warn('showModal error', err);
        }
      }
      tries--;
      if (tries > 0) setTimeout(attempt, delay);
      else console.warn('Modal not shown: bootstrap or element unavailable:', modalId);
    })();
  }

  // attach handlers for ongoing modal
  function attachOngoingHandlers(bsModal) {
    const packetId = ongoingTest ? ongoingTest.packet_id : null;

    const btnContinue = document.getElementById('btn-continue-test');
    const btnCancel   = document.getElementById('btn-cancel-test');

    if (btnContinue) {
      btnContinue.addEventListener('click', function (e) {
        e.preventDefault();
        bsModal.hide();
        const targetUrl = '/soal' + (packetId ? ('?packet_id=' + encodeURIComponent(packetId)) : '');
        window.location.href = targetUrl;
      });
    }

    if (btnCancel) {
      btnCancel.addEventListener('click', function (e) {
        e.preventDefault();
        // clear client storage
        try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
        if (packetId) {
          const keys = [
            `quizStartTime_${packetId}`,
            `quizCurrent_${packetId}`,
            `quizPerStart_${packetId}`,
            `quizPerEnd_${packetId}`,
            `quizPerCurrent_${packetId}`,
            `quizTotalStart_${packetId}`,
            `quizTotalDuration_${packetId}`
          ];
          keys.forEach(k => { try { localStorage.removeItem(k); } catch(e) {} });
        } else {
          ['quizStartTime','quizCurrent','quizTotalStart','quizTotalDuration'].forEach(k => {
            try { localStorage.removeItem(k); } catch(e) {}
          });
        }

        // notify server to forget session packet (CSRF meta required)
        fetch("{{ route('test.cancel') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
          },
          body: JSON.stringify({ packet_id: packetId })
        }).then(resp => resp.json()).finally(() => {
          bsModal.hide();
          location.reload();
        });
      });
    }
  }

  // attach handlers for welcome modal
  function attachWelcomeHandlers(bsModal) {
    const btnStart = document.getElementById('btn-welcome-start');
    const btnLater = document.getElementById('btn-welcome-later');

    if (btnStart) {
      btnStart.addEventListener('click', function (e) {
        e.preventDefault();
        bsModal.hide();
        setTimeout(function () {
          const tutorialEl = document.getElementById('tutorialModal');
          if (tutorialEl && typeof bootstrap !== 'undefined') {
            const tutModal = new bootstrap.Modal(tutorialEl);
            tutModal.show();
          } else {
            const confirmEl = document.getElementById('confirmModal');
            if (confirmEl && typeof bootstrap !== 'undefined') {
              const conf = new bootstrap.Modal(confirmEl);
              conf.show();
            } else {
              // fallback: direct to start route (adjust route if needed)
              window.location.href = "{{ route('soal.start') }}";
            }
          }
        }, 220);
      });
    }

    if (btnLater) {
      btnLater.addEventListener('click', function () {
        // user chose to see later — nothing else
      });
    }
  }

  // show desired modal and attach handlers
  if (hasOngoing) {
    showModalWhenReady('modalOngoingTest', attachOngoingHandlers);
  } else {
    showModalWhenReady('modalWelcomeTest', attachWelcomeHandlers);
  }

});
</script>
@endsection

@endsection