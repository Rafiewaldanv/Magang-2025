@extends('template/main')

@section('content')
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
