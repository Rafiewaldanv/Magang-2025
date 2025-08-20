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
                    {{-- Status --}}
                    <h3 class="fw-bold text-success mb-3">
                        {{ $status ?? 'selesai' }}
                    </h3>

                    {{-- Message --}}
                    <p class="lead mb-4">
                        {{ $message ?? 'Test berhasil diselesaikan!' }}
                    </p>

                    {{-- Hasil --}}
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

