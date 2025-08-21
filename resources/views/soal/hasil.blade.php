@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

{{-- Header --}}
<div class="bg-theme-1 bg-header">
  <div class="container text-center text-white">
    <h2 id="timer">00:00</h2>
  </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" 
        viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,
                 600,112.77S1200,65.52,1200,7.23V0Z" 
              class="shape-fill"></path>
    </svg>
</div>

{{-- Content --}}
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg rounded-3">
                <div class="card-body text-center p-5">

                    {{-- ===== determine whether to show score for this packet ===== --}}
                    @php
                        // konfigurasi: daftar packet id yang menampilkan skor
                        // developer: ubah array ini sesuai kebutuhan
                        $showScorePacketIds = isset($showScorePacketIds) ? $showScorePacketIds : [10000];

                        // cari packet id yang aktif (coba beberapa var)
                        $currentPacketId = null;
                        if (isset($packetId) && $packetId !== '') {
                            $currentPacketId = (int) $packetId;
                        } elseif (isset($packet) && !empty($packet->id)) {
                            $currentPacketId = (int) $packet->id;
                        } elseif (isset($packet_id) && $packet_id !== '') {
                            $currentPacketId = (int) $packet_id;
                        }

                        $showScore = in_array($currentPacketId, $showScorePacketIds, true);
                    @endphp

                    {{-- ===== PACKET NAME (robust fallback) ===== --}}
                    @php
                        $packetDisplayName = null;

                        if (isset($packet_name) && !empty($packet_name)) {
                            $packetDisplayName = $packet_name;
                        } elseif (isset($packetName) && !empty($packetName)) {
                            $packetDisplayName = $packetName;
                        } elseif (session('packetName')) {
                            $packetDisplayName = session('packetName');
                        } elseif (isset($packet) && !empty($packet->name)) {
                            $packetDisplayName = $packet->name;
                        } elseif (!empty($currentPacketId)) {
                            $packetDisplayName = 'Paket #' . $currentPacketId;
                        }

                        if (empty($packetDisplayName)) {
                            $packetDisplayName = 'Paket Tes';
                        }
                    @endphp

                    <div class="mb-3">
                        <div class="result-packet-badge" title="{{ $packetDisplayName }}">
                            {{ $packetDisplayName }}
                        </div>
                    </div>
                    {{-- ===== end packet name ===== --}}

                    {{-- Conditional: show score or simple saved message --}}
                    @if($showScore)
                        {{-- Score badge & dynamic text --}}
                        @php
                            $score = isset($result['score']) ? (int) $result['score'] : null;
                            // default classes/text
                            $scoreClass = 'score-neutral';
                            $scoreText = $status ?? 'Selesai';
                            $subText = $message ?? 'Test berhasil diselesaikan!';
                            if ($score !== null) {
                                if ($score > 75) {
                                    $scoreClass = 'score-green';
                                    $subText = 'Anda lulus';
                                } elseif ($score > 50) {
                                    $scoreClass = 'score-yellow';
                                    $subText = 'Anda KKM saja';
                                } else {
                                    $scoreClass = 'score-red';
                                    $subText = 'Anda tidak lulus, mohon mengulang';
                                }
                            }
                        @endphp

                        <div class="d-flex justify-content-center mb-4">
                            <div class="score-badge {{ $scoreClass }}" role="status" aria-label="Score">
                                @if($score !== null)
                                    <span class="score-number">{{ $score }}<small class="percent">%</small></span>
                                @else
                                    <span class="score-number">-</span>
                                @endif
                            </div>
                        </div>

                        {{-- small result message based on color --}}
                        <h4 class="fw-bold mb-2 text-capitalize">{{ $score !== null ? $scoreText : ($status ?? 'Selesai') }}</h4>
                        <p class="mb-4 result-subtext {{ $scoreClass }}-text">{{ $subText }}</p>

                        {{-- Hasil detail (tetap ada, tidak diubah) --}}
                        @if(isset($result))
                            <div class="row mb-4">
                                <div class="col-md-6 text-start">
                                    <p><strong>Score:</strong> {{ $result['score'] }}</p>
                                    <p><strong>Total Benar:</strong> {{ $result['total_correct'] }}</p>
                                </div>
                                <div class="col-md-6 text-start">
                                    <p><strong>Total Salah:</strong> {{ $result['total_wrong'] }}</p>
                                    <p><strong>Total Soal:</strong> {{ $result['total_question'] }}</p>
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- Simple confirmation message for packets that SHOULD NOT show score --}}
                        <div class="mb-4">
                          <div class="alert alert-success py-4" role="alert" style="font-size:1.05rem;">
                            <h4 class="alert-heading">Selamat!</h4>
                            <p class="mb-0">Jawaban Anda telah tersimpan.</p>
                          </div>
                        </div>

                        {{-- optionally show minimal info --}}
                        <p class="text-muted mb-4">Terima kasih telah menyelesaikan tes. Hasil akhir tidak ditampilkan untuk paket ini.</p>
                    @endif

                    {{-- Tombol Redirect --}}
                    <a href="{{ $redirect ?? url('/home') }}" class="btn btn-primary btn-lg">
                        Kembali ke Home
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('css-extra')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
/* Packet badge on result page */
.result-packet-badge{
  display: inline-block;
  background: linear-gradient(90deg, #ff7a18 0%, #ffb347 100%);
  color: #fff;
  padding: 8px 14px;
  border-radius: 999px;
  font-weight: 700;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  font-size: 1rem;
}

/* Score badge */
.score-badge{
  width: 100px;
  height: 100px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  font-weight: 700;
}

/* Number style */
.score-number{
  font-size: 2.6rem;
  line-height: 1;
  color: #fff;
}
.score-number .percent{
  font-size: 0.6rem;
  margin-left: 4px;
  vertical-align: top;
}

/* color variants */
.score-green{ background: #28a745; }   /* success */
.score-yellow{ background: #ffc107; color: #212529; } /* warning */
.score-red{ background: #dc3545; }     /* danger */
.score-neutral{ background: #6c757d; }

/* Subtext color matching */
.score-green-text{ color: #28a745; font-weight:600; }
.score-yellow-text{ color: #8a6d00; font-weight:600; } /* darker yellow-ish */
.score-red-text{ color: #dc3545; font-weight:600; }

/* keep responsiveness */
@media (max-width: 576px){
  .score-badge{ width: 120px; height: 120px; }
  .score-number{ font-size: 2rem; }
  .result-packet-badge { font-size: 0.95rem; padding: 8px 12px; }
}
</style>
@endsection

@section('js-extra')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // bersihkan storage (opsional)
  try { sessionStorage.removeItem('jawabanSementara'); } catch(e) {}
  // hapus localStorage keys kalau perlu
  // try { localStorage.removeItem('quizStartTime_'+packetId); } catch(e) {}

  // Buat satu history state supaya popstate tersedia, lalu tangani popstate
  try {
    history.replaceState({}, '', window.location.href); // normalisasi
    history.pushState({}, '', window.location.href);    // push satu state
  } catch(e) {}

  // Saat user tekan Back (popstate), langsung redirect ke home
  window.addEventListener('popstate', function (e) {
    // ganti URL ke home langsung
    window.location.href = "{{ route('home') }}";
  });

  // juga override any link/button yang mungkin mencoba ke /soal
  document.querySelectorAll('a[href*="/soal"]').forEach(function(a){
    a.addEventListener('click', function(e){
      e.preventDefault();
      window.location.href = "{{ route('home') }}";
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const packetIds = @json($packetIds ?? ($packetId ? [$packetId] : []));
  packetIds.forEach(id => {
    try {
      localStorage.removeItem(`quizStartTime_${id}`);
      localStorage.removeItem(`quizCurrent_${id}`);
      localStorage.removeItem(`quizPerStart_${id}`);
      localStorage.removeItem(`quizPerEnd_${id}`);
      localStorage.removeItem(`quizPerCurrent_${id}`);
      localStorage.removeItem(`quizTotalStart_${id}`);
      localStorage.removeItem(`quizTotalDuration_${id}`);
    } catch(e){}
  });
  try { sessionStorage.removeItem('jawabanSementara'); } catch(e){}
  // Pastikan back dari halaman hasil mengarah ke home, bukan kembali ke /soal/simpan
  try {
    history.replaceState({}, '', window.location.href);
    history.pushState({}, '', window.location.href);
    window.addEventListener('popstate', function () {
      window.location.href = "{{ route('home') }}";
    });
  } catch(e){}
});
</script>
@endsection
