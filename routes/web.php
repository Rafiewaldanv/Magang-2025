<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🏠 Halaman default: Tampilkan homepage
Route::get('/', function () {
    return view('home'); // ⬅️ Menampilkan resources/views/home.blade.php
});

// Route utama tes
Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');

// Ambil soal by nomor
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// Simpan jawaban per soal
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// Submit otomatis di akhir tes
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');

// Selesai
Route::get('/tes/selesai', fn() => view('tes.selesai'))->name('tes.selesai');

