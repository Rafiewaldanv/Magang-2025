@extends('template/main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading" style="display: none;">
    <div class="spinner"></div>
</div>

<div class="bg-header" style="background-color: rgb(255, 165, 0);">
    <div class="container text-center text-white py-4">
        <h2 class="fw-bold">Pilih Tes</h2>
        <p>Silakan pilih tes yang ingin kamu ikuti. Kamu harus login terlebih dahulu untuk memulai tes.</p>
    </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" style="fill: rgb(255, 165, 0);"></path>
    </svg>
</div>

<div class="container my-5">
    @guest
        <div class="alert alert-warning text-center">
            <strong>Login terlebih dahulu</strong> untuk dapat mengikuti tes.
            <br>
            <a href="{{ route('login') }}" class="btn btn-warning mt-3">Login Sekarang</a>
        </div>
    @else
        @if(count($tests) > 0)
            <div class="row">
                @foreach($tests as $test)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $test->name }}</h5>
                                <p class="card-text mb-1"><strong>Jumlah Soal:</strong> {{ $test->total_questions }}</p>
                                <p class="card-text mb-1"><strong>Durasi:</strong> {{ $test->duration }} menit</p>
                                <p class="card-text text-muted small">{{ $test->description }}</p>
                                <pre>{{ var_dump($test->id) }}</pre>

                                <<a href="{{ route('soal.index', ['id' => $test->id]) }}" class="btn btn-warning">
    Mulai Tes
</a>


                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                Tidak ada tes yang tersedia saat ini.
            </div>
        @endif
    @endguest
</div>
@endsection

@section('js-extra')
<!-- Tambahan JS jika ada -->
@endsection

@section('css-extra')
<!-- Tambahan CSS jika ada -->
@endsection
