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
                        {{ $status ?? 'Jawaban Tersimpan' }}
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
