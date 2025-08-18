<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🏠 Halaman default - redirect ke halaman soal
Route::get('/', function () {
    return redirect()->route('home');
});
// Import Soal Ke Database
Route::get('/soal-adaptif-analogi', [SoalController::class, 'SoalAdaptifAnalogi'])->name('soal.adaptif.analogi');
Route::get('/soal-adaptif-penalaran', [SoalController::class, 'SoalAdaptifPenalaran'])->name('soal.adaptif.penalaran');
Route::get('/soal-adaptif-spasial-3d', [SoalController::class, 'SoalAdaptifSpasial3D'])->name('soal.adaptif.spasial3d');
Route::get('/soal-adaptif-spasial', [SoalController::class, 'SoalAdaptifSpasial'])->name('soal.adaptif.spasial');
Route::get('/soal-akurasi', [SoalController::class, 'SoalAkurasi'])->name('soal.akurasi');
Route::get('/soal-penalaran', [SoalController::class, 'SoalPenalaran'])->name('soal.penalaran');
Route::get('/soal-spasial', [SoalController::class, 'SoalSpasial'])->name('soal.spasial');
Route::get('/soal-holland-riasec', [SoalController::class, 'SoalHollandRiasec'])->name('soal.holland.riasec');
Route::get('/soal-spasial-3d', [SoalController::class, 'SoalSpasial3D'])->name('soal.spasial.3d');
Route::get('/soal-toeic', [SoalController::class, 'SoalToeic'])->name('soal.toeic');

// 📃 View utama tes interaktifRoute::get('/soal-toeic', [SoalController::class, 'tampilkanSoalToeic']);
Route::get('/home', [SoalController::class, 'home'])->name('home');
Route::post('/soal/start', [SoalController::class, 'soalStart'])->name('soal.start');
Route::post('/soal/prepare', [SoalController::class, 'prepareTes'])->name('soal.mulai');
Route::get('/soal', [SoalController::class, 'mulaiTes'])->name('soal.index');




// 📦 API: Ambil soal berdasarkan nomor (dipanggil dari JS)
// 📮 Simpan jawaban setelah memilih opsi (optional, jika dipakai per soal)
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');
Route::get('/error', function () {
    return view('soal.error');
})->name('soal.error');
Route::get('/errorr', function () {
    return view('soal.error');
})->name('soal.errorr');
Route::get('/errorrr', function () {
    return view('soal.error');
})->name('soal.errorrr');
// 🚀 Submit saat waktu habis (auto-submit final)
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');

// 🧪  View setelah tes selesai
Route::get('/tes/selesai', function () {  return view('tes.selesai'); // Buat view ini untuk notifikasi akhir tes
})->name('tes.selesai');

Route::get('/soal/selesai}', [SoalController::class, 'soalSelesai'])->name('soal.simpan');