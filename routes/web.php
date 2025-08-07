<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🏠 Halaman default
Route::get('/', function () {
    return view('home');
});

// 📃 Halaman soal interaktif
Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');

// 📦 API: Ambil soal via nomor (dipanggil oleh JS)
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// 📝 Simpan jawaban secara parsial (opsional, via AJAX)
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// 🚀 Auto-submit dari tombol submit
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');

// ✅ Koreksi akhir & tampilkan hasil di soal/results.blade.php
Route::post('/soal/store', [SoalController::class, 'store'])->name('soal.store');
