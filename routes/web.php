<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🏠 Halaman default: Tampilkan homepage
Route::get('/', function () {
    return view('home'); // ⬅️ Menampilkan resources/views/home.blade.php
});

// 📃 View utama tes interaktif
Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');

// 📦 API: Ambil soal berdasarkan nomor (dipanggil dari JS)
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// 📮 Simpan jawaban setelah memilih opsi (optional, jika dipakai per soal)
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// 🚀 Submit saat waktu habis (auto-submit final)
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');

// 🧪 (Optional) View setelah tes selesai
Route::get('/tes/selesai', function () {
    return view('tes.selesai'); // Buat view ini untuk notifikasi akhir tes
})->name('tes.selesai');
