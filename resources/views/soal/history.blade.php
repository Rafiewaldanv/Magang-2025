@extends('template.main')

@section('content')
<!-- Overlay Loading -->
<div id="overlay-loading">
    <div class="spinner"></div>
</div>

<!-- Header -->
<div class="bg-header" style="background-color: rgb(255, 165, 0);">
    <div class="container text-center text-white">
        <h2 class="mb-0">Riwayat Tes</h2>
        <p class="mt-2">Lihat semua tes yang sudah kamu kerjakan</p>
    </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" style="fill: rgb(255, 165, 0);"></path>
    </svg>
</div>

<!-- Isi -->
<div class="container main-container mt-5 mb-5">
    <div class="card shadow mx-auto" style="max-width: 900px">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Riwayat Tes Anda</h4>

            @if($results->isEmpty())
                <p class="text-center text-muted">Belum ada tes yang dikerjakan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-warning">
                            <tr>
                                <th>No</th>
                                <th>Nama Tes</th>
                                <th>Paket</th>
                                <th>Nilai / Hasil</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $result)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $result->test->name ?? '-' }}</td>
                                    <td>{{ $result->packet->name ?? '-' }}</td>
                                    <td>{{ $result->result }}</td>
                                    <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                    <a href="{{ route('soal.results', $result->id) }}">Lihat Detail</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection