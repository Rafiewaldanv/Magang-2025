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

                <form id="startTestForm" method="GET" action="{{ route('soal.index') }}">
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

@endsection
