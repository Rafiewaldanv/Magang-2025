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

<!-- Modal Tutorial -->
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

<!-- Modal 2: Pilih Paket (dari tutorial -> confirm) -->
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

<!-- Modal Ongoing Test (muncul otomatis bila $ongoingTest ada) -->
@php
  // Ambil packet id & name dari ongoingTest (dukungan array / object)
  $ongoingPacketId = null;
  $ongoingPacketName = null;

  if (!empty($ongoingTest)) {
    if (is_array($ongoingTest)) {
      $ongoingPacketId = $ongoingTest['packet_id'] ?? null;
      $ongoingPacketName = $ongoingTest['packet_name'] ?? null;
    } else {
      $ongoingPacketId = $ongoingTest->packet_id ?? null;
      $ongoingPacketName = $ongoingTest->packet_name ?? null;
    }
  }

  // Jika name belum ada, coba cari di koleksi $packets (jika controller mengirimkan)
  if (empty($ongoingPacketName) && !empty($ongoingPacketId) && !empty($packets)) {
    foreach ($packets as $p) {
      $pId = is_object($p) ? ($p->id ?? null) : ($p['id'] ?? null);
      $pName = is_object($p) ? ($p->name ?? null) : ($p['name'] ?? null);
      if ((string)$pId === (string)$ongoingPacketId) {
        $ongoingPacketName = $pName ?? null;
        break;
      }
    }
  }

  // Jika masih kosong, coba ambil langsung dari model Packet (DB lookup) sebagai fallback
  if (empty($ongoingPacketName) && !empty($ongoingPacketId)) {
    try {
      $pkt = \App\Models\Packet::find($ongoingPacketId);
      if ($pkt && !empty($pkt->name)) {
        $ongoingPacketName = $pkt->name;
      }
    } catch (\Throwable $e) {
      // silent fail — biarkan fallback nanti
    }
  }

  // fallback final
  if (empty($ongoingPacketName)) {
    $ongoingPacketName = 'Paket Tes';
  }
@endphp

@if(!empty($ongoingTest))
  <div class="modal fade" id="modalOngoingTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tes Sedang Berlangsung</h5>
          <button type="button" class="btn-close" id="modal-close-btn" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <p>Anda sedang mengerjakan:</p>

          {{-- tampilkan nama paket dan sertakan data-packet-id supaya JS mudah akses --}}
          <h5 class="fw-bold" id="modal-packet-name" data-packet-id="{{ e($ongoingPacketId) }}">
            {{ e($ongoingPacketName) }}
          </h5>

          <div class="mt-3">
            <small class="text-muted">Sisa Waktu:</small>
            <div class="h3 fw-bold" id="modal-remaining">--:--</div>
            <div class="small text-muted" id="modal-remaining-note"></div>
          </div>

          <p class="mt-3 text-muted">Jika keluar sekarang, tes akan dianggap dibatalkan.</p>
        </div>

        <div class="modal-footer">
          <button type="button" id="btn-cancel-test" class="btn btn-secondary">Tidak, Batalkan</button>
          <a href="#" id="btn-continue-test" class="btn btn-primary">Lanjutkan Tes</a>
        </div>
      </div>
    </div>
  </div>
@endif




<!-- Modal Kembali (untuk mencegah back langsung) -->
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

@endsection

{{-- JS section: pastikan hanya satu section js-extra per file --}}
@section('js-extra')
@parent
<script>
  const ongoingTest = @json($ongoingTest ?? null);

// 1) coba ambil dari object ongoingTest
let packetId = ongoingTest && (ongoingTest.packet_id ?? ongoingTest.packetId ?? ongoingTest.id) ? String(ongoingTest.packet_id ?? ongoingTest.packetId ?? ongoingTest.id) : null;
let packetName = ongoingTest && (ongoingTest.packet_name ?? ongoingTest.packetName) ? (ongoingTest.packet_name ?? ongoingTest.packetName) : null;

// 2) fallback: ambil dari DOM (diset oleh Blade)
const nameEl = document.getElementById('modal-packet-name');
if ((!packetId || packetId === 'null') && nameEl && nameEl.dataset && nameEl.dataset.packetId) {
  packetId = String(nameEl.dataset.packetId);
}
if (!packetName && nameEl) {
  // gunakan textContent yang sudah diisi Blade
  packetName = (nameEl.textContent || nameEl.innerText || '').trim() || packetName;
}

// 3) fallback akhir: gunakan value dari Blade (disisipkan langsung) — ini aman karena Blade sudah menentukan ongoingPacketName
packetId = packetId || "{{ $ongoingPacketId ?? '' }}";
packetName = packetName || "{{ addslashes($ongoingPacketName ?? 'Paket Tes') }}";

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('modalOngoingTest');
  if (!modalEl) return;

  // Buat instance Bootstrap modal dengan opsi yang mencegah close via backdrop / ESC
  const bsModal = new bootstrap.Modal(modalEl, {
    backdrop: 'static',
    keyboard: false
  });

  // Tampilkan modal
  bsModal.show();

  // Pastikan tombol X tidak melakukan apa-apa (defensive)
  const closeBtn = document.getElementById('modal-close-btn');
  if (closeBtn) {
    closeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      // optional: tampilkan pesan kecil agar user tahu harus pilih salah satu tombol di modal
      // contoh sederhana (uncomment jika mau):
      // alert('Silakan pilih "Lanjutkan Tes" atau "Tidak, Batalkan" terlebih dahulu.');
    });
  }

  // Jika kamu ingin tombol "Tidak, Batalkan" menutup modal lalu melakukan cleanup, handle di sini:
  const btnCancel = document.getElementById('btn-cancel-test');
  if (btnCancel) {
    btnCancel.addEventListener('click', function (e) {
      e.preventDefault();

      // contoh cleanup: hapus storage client & panggil endpoint cancel jika perlu
      try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
      // hapus localStorage yg terkait packet bila perlu (ganti nama key sesuai implementasimu)
      // localStorage.removeItem(`quizStartTime_${packetId}`);

      // lakukan request ke server untuk forget session (opsional)
      fetch("{{ route('test.cancel') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
        },
        body: JSON.stringify({ packet_id: (document.getElementById('modal-packet-name')?.dataset?.packetId || null) })
      }).finally(() => {
        bsModal.hide();
        // reload supaya UI menyesuaikan
        location.reload();
      });
    });
  }

  // tombol lanjutkan akan diarahkan oleh code lain; jika mau set link:
  const btnContinue = document.getElementById('btn-continue-test');
  if (btnContinue) {
    // contoh: arahkan ke /soal?packet_id=... (sesuaikan)
    const pkt = document.getElementById('modal-packet-name')?.dataset?.packetId;
    if (pkt) btnContinue.setAttribute('href', '/soal?packet_id=' + encodeURIComponent(pkt));
    // Bisa juga langsung hide modal on click:
    btnContinue.addEventListener('click', function () {
      // bsModal.hide(); // opsional
    });
  }

});
</script>

<script>
/**
 * Modal Ongoing handler + countdown that uses the same localStorage keys
 * as your quiz-render.js. It will:
 * - show packet name
 * - compute remaining time from localStorage keys (total or per-question mode)
 * - if not started yet, show full duration and "Belum dimulai"
 * - when Continue pressed: ensure start keys exist then redirect to /soal?packet_id=...
 * - when Cancel pressed: clear keys and call server cancel
 *
 * Requires server variable: `ongoingTest` (packet_id, packet_name, duration_minutes optional)
 */
document.addEventListener('DOMContentLoaded', function () {
  const ongoingTest = @json($ongoingTest ?? null);
  if (!ongoingTest || !ongoingTest.packet_id) return;

  const packetId = String(ongoingTest.packet_id);
  const packetName = ongoingTest.packet_name || 'Paket Tes';
  const durationMinutesFromServer = parseInt(ongoingTest.duration_minutes || 0, 10);

  // localStorage keys — must match keys used in quiz-render.js
  const currentKey = `quizCurrent_${packetId}`;
  const totalStartKey = `quizTotalStart_${packetId}`;
  const totalDurationKey = `quizTotalDuration_${packetId}`;
  const perStartKey = `quizPerStart_${packetId}`;
  const perEndKey = `quizPerEnd_${packetId}`;
  const perCurrentKey = `quizPerCurrent_${packetId}`;

  const nameEl = document.getElementById('modal-packet-name');
  const remainingEl = document.getElementById('modal-remaining');
  const noteEl = document.getElementById('modal-remaining-note');

  if (nameEl) nameEl.textContent = packetName;

  const perQuestionMode = parseInt(packetId, 10) === 7;
  const perQuestionDuration = 15 * 1000; // 15s

  function pad(n){ return String(n).padStart(2,'0'); }

  function readTotalDurationMs() {
    const persisted = parseInt(localStorage.getItem(totalDurationKey), 10);
    if (!isNaN(persisted) && persisted > 0) return persisted;
    if (!isNaN(durationMinutesFromServer) && durationMinutesFromServer > 0) {
      return durationMinutesFromServer * 60 * 1000;
    }
    return 30 * 60 * 1000; // fallback
  }

  function getTotalRemainingMs() {
    const totalDurationMs = readTotalDurationMs();
    const savedStart = parseInt(localStorage.getItem(totalStartKey), 10);
    if (!isNaN(savedStart) && savedStart > 0) {
      const rem = (savedStart + totalDurationMs) - Date.now();
      return rem > 0 ? rem : 0;
    }
    return totalDurationMs;
  }

  function getPerQuestionRemainingMs() {
    const savedEnd = parseInt(localStorage.getItem(perEndKey), 10);
    if (!isNaN(savedEnd) && savedEnd > Date.now()) {
      return savedEnd - Date.now();
    }
    return perQuestionDuration;
  }

  let countdownInterval = null;
  function updateCountdownUI() {
    let remMs = perQuestionMode ? getPerQuestionRemainingMs() : getTotalRemainingMs();
    const totalSeconds = Math.floor(remMs / 1000);
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    if (remainingEl) remainingEl.textContent = `${pad(minutes)}:${pad(seconds)}`;

    if (perQuestionMode) {
      const hasPerStart = !!localStorage.getItem(perStartKey) || !!localStorage.getItem(perEndKey);
      noteEl.textContent = hasPerStart ? '' : 'Belum dimulai — durasi per-soal penuh ditampilkan.';
    } else {
      const hasTotalStart = !!localStorage.getItem(totalStartKey);
      noteEl.textContent = hasTotalStart ? '' : 'Belum dimulai — durasi penuh ditampilkan.';
    }
  }

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

  function attachHandlers(bsModal) {
    updateCountdownUI();
    countdownInterval = setInterval(updateCountdownUI, 1000);

    const btnContinue = document.getElementById('btn-continue-test');
    if (btnContinue) {
      btnContinue._listener && btnContinue.removeEventListener('click', btnContinue._listener);
      btnContinue._listener = function (e) {
        e.preventDefault();
        if (perQuestionMode) {
          if (!localStorage.getItem(perStartKey) || !localStorage.getItem(perEndKey)) {
            const now = Date.now();
            localStorage.setItem(perStartKey, String(now));
            localStorage.setItem(perEndKey, String(now + perQuestionDuration));
            localStorage.setItem(perCurrentKey, String(parseInt(localStorage.getItem(perCurrentKey) || '1', 10)));
          }
        } else {
          if (!localStorage.getItem(totalStartKey)) {
            const now = Date.now();
            localStorage.setItem(totalStartKey, String(now));
            localStorage.setItem(totalDurationKey, String(readTotalDurationMs()));
          }
        }
        bsModal.hide();
        window.location.href = '/soal' + (packetId ? ('?packet_id=' + encodeURIComponent(packetId)) : '');
      };
      btnContinue.addEventListener('click', btnContinue._listener);
    }

    const btnCancel = document.getElementById('btn-cancel-test');
    if (btnCancel) {
      btnCancel._listener && btnCancel.removeEventListener('click', btnCancel._listener);
      btnCancel._listener = function (e) {
        e.preventDefault();
        try {
          localStorage.removeItem(currentKey);
          localStorage.removeItem(totalStartKey);
          localStorage.removeItem(totalDurationKey);
          localStorage.removeItem(perStartKey);
          localStorage.removeItem(perEndKey);
          localStorage.removeItem(perCurrentKey);
        } catch (err) { console.warn(err); }
        try { sessionStorage.removeItem('jawabanSementara'); } catch(e) {}

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
      };
      btnCancel.addEventListener('click', btnCancel._listener);
    }

    const modalEl = document.getElementById('modalOngoingTest');
    if (modalEl) {
      modalEl.addEventListener('hidden.bs.modal', function onHidden() {
        if (countdownInterval) {
          clearInterval(countdownInterval);
          countdownInterval = null;
        }
        modalEl.removeEventListener('hidden.bs.modal', onHidden);
      });
    }
  }

  showModalWhenReady('modalOngoingTest', attachHandlers);
});
</script>
@endsection
