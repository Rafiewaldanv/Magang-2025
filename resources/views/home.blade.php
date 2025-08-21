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

<!-- Modal Ongoing Test (muncul otomatis bila $ongoingTest ada) -->
@if(!empty($ongoingTest))
  <style>
    /* simple modern styling for the ongoing modal */
    .modal-simple .modal-content {
      border: 0;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    .modal-simple .modal-header {
      padding: 16px 18px;
      border-bottom: 0;
      background: #fff;
    }
    .modal-simple .modal-title { font-weight:700; margin:0; }
    .modal-simple .modal-sub { font-size:.88rem; color:#6c757d; margin-top:4px; }
    .modal-simple .modal-body { padding: 16px 18px; background: #fbfbfc; }
    .modal-simple .status-pill {
      display:inline-flex;
      align-items:center;
      padding:.28rem .7rem;
      border-radius:999px;
      font-weight:600;
      font-size:.88rem;
      background:#fff3cd;
      color:#856404;
    }
    .modal-simple .status-ended {
      background:#f8d7da;
      color:#842029;
    }
    .modal-simple .pkg-name { font-weight:700; margin-bottom:.15rem; }
    .modal-simple .meta { font-size:.86rem; color:#6c757d; }
    .modal-simple .current-q { font-weight:600; font-size:1rem; }
    .modal-simple .btn-ghost {
      border:1px solid rgba(0,0,0,0.06);
      background:#fff;
      color:#212529;
    }
    @media (max-width:420px) { .modal-dialog { margin:1rem; } }
  </style>

  <div class="modal fade modal-simple" id="modalOngoingTest" tabindex="-1" aria-hidden="true" aria-labelledby="modalOngoingTitle" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between align-items-start">
          <div>
            <h5 id="modalOngoingTitle" class="modal-title">Peringatan</h5>
            <div class="modal-sub">Ada tes yang sedang berlangsung — lanjutkan pada perangkat yang sama.</div>
          </div>
          <button type="button" class="btn-close" id="modal-close-btn" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2 meta">Anda sedang mengerjakan</div>

          <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
              <div id="modal-packet-name" class="pkg-name" data-packet-id="{{ e($ongoingPacketId) }}">{{ e($ongoingPacketName) }}</div>
              <div id="modal-remaining-note" class="meta" aria-live="polite"></div>
            </div>

            <div class="text-end">
              <div class="meta">Status terakhir</div>
              <div id="modal-remaining" class="status-pill" aria-live="polite">Ongoing</div>
            </div>
          </div>

          <div class="mb-2">
            <div class="meta">Terakhir di soal</div>
            <div id="modal-current-question" class="current-q">Nomor -</div>
          </div>

          <div class="d-grid gap-2 mt-3">
            <button type="button" id="btn-cancel-test" class="btn btn-ghost btn-lg">Tidak, Batalkan</button>
            <a href="#" id="btn-continue-test" class="btn btn-primary btn-lg">Lanjutkan Tes</a>
          </div>

          <p class="mt-3 mb-0 small text-muted">Jika keluar sekarang, tes akan dianggap dibatalkan dan tidak bisa dilanjutkan lagi.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
  (function(){
    try {
      const ongoing = @json($ongoingTest ?? null);
      if (!ongoing || !ongoing.packet_id) return;

      const pid = String(ongoing.packet_id);
      const statusEl = document.getElementById('modal-remaining');
      const noteEl = document.getElementById('modal-remaining-note');
      const packetNameEl = document.getElementById('modal-packet-name');
      const currentQuestionEl = document.getElementById('modal-current-question');

      // keys used by quiz code
      const perStartKey = `quizPerStart_${pid}`;
      const perEndKey = `quizPerEnd_${pid}`;
      const totalStartKey = `quizTotalStart_${pid}`;
      const currentKey = `quizCurrent_${pid}`;
      const finishedKey = `quizFinished_${pid}`; // optional

      function readLS(k){ try { return localStorage.getItem(k); } catch(e){ return null; } }
      function readIntLS(k){ const v=readLS(k); if (!v) return NaN; const n=parseInt(v,10); return isNaN(n)?NaN:n; }

      function isFinishedByStorage(){
        try {
          if (readLS(finishedKey)) return true;
          const hasPerStart = !!readLS(perStartKey);
          const hasPerEnd = !!readLS(perEndKey);
          const hasTotalStart = !!readLS(totalStartKey);
          const hasCurrent = !isNaN(readIntLS(currentKey));
          return !(hasPerStart || hasPerEnd || hasTotalStart || hasCurrent);
        } catch(e){ return false; }
      }

      function setStatusOngoing(){
        if(!statusEl) return;
        statusEl.textContent = 'Ongoing';
        statusEl.classList.remove('status-ended');
        statusEl.classList.add('status-pill');
        statusEl.style.background = '#fff3cd';
        statusEl.style.color = '#856404';
      }
      function setStatusEnded(){
        if(!statusEl) return;
        statusEl.textContent = 'Berakhir';
        statusEl.classList.add('status-ended');
        statusEl.style.background = '#f8d7da';
        statusEl.style.color = '#842029';
      }

      function getCurrentQuestion(){
        const saved = readIntLS(currentKey);
        if (!isNaN(saved) && saved > 0) return saved;
        const s = parseInt(ongoing.current_question ?? ongoing.currentQuestion ?? 0, 10);
        if (!isNaN(s) && s > 0) return s;
        return null;
      }

      function updateUI(){
        if (packetNameEl && ongoing.packet_name) packetNameEl.textContent = ongoing.packet_name;
        if (noteEl) noteEl.textContent = ongoing.info ?? '';

        const serverFinished = !!(ongoing.is_finished || ongoing.finished || ongoing.status === 'finished');
        const finished = serverFinished || isFinishedByStorage();
        if (finished) setStatusEnded(); else setStatusOngoing();

        const cur = getCurrentQuestion();
        currentQuestionEl.textContent = cur ? ('Nomor ' + cur) : 'Nomor -';
      }

      updateUI();

      // listen storage changes (tab sync)
      window.addEventListener('storage', function(ev){
        if (!ev.key) { updateUI(); return; }
        const relevant = [perStartKey, perEndKey, totalStartKey, currentKey, finishedKey];
        if (relevant.includes(ev.key)) setTimeout(updateUI, 100);
      }, false);

      // poll fallback while modal open
      const poll = setInterval(updateUI, 1500);
      const modalEl = document.getElementById('modalOngoingTest');
      if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function(){ clearInterval(poll); }, { once:true });
      }

      // wire continue button
      const btnContinue = document.getElementById('btn-continue-test');
      if (btnContinue) {
        const pkt = packetNameEl?.dataset?.packetId || pid;
        btnContinue.setAttribute('href', '/soal?packet_id=' + encodeURIComponent(pkt));
      }

      // cancel behavior
      const btnCancel = document.getElementById('btn-cancel-test');
      if (btnCancel) {
        btnCancel.addEventListener('click', function(e){
          e.preventDefault();
          try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
          fetch("{{ route('test.cancel') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') },
            body: JSON.stringify({ packet_id: pid })
          }).finally(()=> { const bs = bootstrap.Modal.getInstance(modalEl); if(bs) bs.hide(); location.reload(); });
        });
      }

      // prevent close via X button
      const closeBtn = document.getElementById('modal-close-btn');
      if (closeBtn) closeBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); });

    } catch(err){
      console.warn('modal ongoing init error', err);
    }
  })();
  </script>
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
  // expose ongoingTest for other scripts if needed
  const ongoingTest = @json($ongoingTest ?? null);

  // derive packetId & packetName robustly (Blade set + DOM fallback)
  let packetId = ongoingTest && (ongoingTest.packet_id ?? ongoingTest.packetId ?? ongoingTest.id) ? String(ongoingTest.packet_id ?? ongoingTest.packetId ?? ongoingTest.id) : null;
  let packetName = ongoingTest && (ongoingTest.packet_name ?? ongoingTest.packetName) ? (ongoingTest.packet_name ?? ongoingTest.packetName) : null;

  const nameEl = document.getElementById('modal-packet-name');
  if ((!packetId || packetId === 'null') && nameEl && nameEl.dataset && nameEl.dataset.packetId) {
    packetId = String(nameEl.dataset.packetId);
  }
  if (!packetName && nameEl) {
    packetName = (nameEl.textContent || nameEl.innerText || '').trim() || packetName;
  }

  packetId = packetId || "{{ $ongoingPacketId ?? '' }}";
  packetName = packetName || "{{ addslashes($ongoingPacketName ?? 'Paket Tes') }}";
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('modalOngoingTest');
  if (!modalEl) return;

  // Show modal in a safe way
  const bsModal = new bootstrap.Modal(modalEl, {
    backdrop: 'static',
    keyboard: false
  });
  bsModal.show();

  // defensive: disable close X behaviour
  const closeBtn = document.getElementById('modal-close-btn');
  if (closeBtn) {
    closeBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
    });
  }

  // wire cancel & continue already handled in modal script above; nothing more needed here
});
</script>

@endsection
