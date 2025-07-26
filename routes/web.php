<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🏠 Halaman awal tes (home)
Route::get('/', function () {
    return view('home'); // tampilkan halaman home.blade.php
})->name('home');

// ▶️ Tombol "Mulai Tes" dari home (redirect ke halaman soal)
Route::post('/mulai-ujian', function () {
    return redirect()->route('soal');
})->name('mulai-ujian');

// 📃 Halaman soal utama
Route::get('/soal', [SoalController::class, 'index'])->name('soal');

// 📦 API: Ambil soal by nomor via AJAX
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// 📝 Simpan jawaban ketika pilih opsi
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// 🚀 Submit seluruh tes (misal ketika klik "Kumpulkan Jawaban")
Route::post('/soal/submit', function () {
    return redirect()->route('tes.selesai');
})->name('soal.submit');

// 🎉 Halaman selesai ujian
Route::get('/tes/selesai', function () {
    return view('tes.selesai'); // view selesai.blade.php disimpan di resources/views/tes/selesai.blade.php
})->name('tes.selesai');
