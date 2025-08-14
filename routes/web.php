<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes (Terintegrasi)
|--------------------------------------------------------------------------
|
| Gabungan routes import, auth, page, API dan hasil test.
| Catatan penting: route name 'soal.index' menunjuk ke mulaiTes($id)
| karena prepareTes() melakukan redirect()->route('soal.index', ['id' => $testId]).
|
*/

// ======= Home & Auth =======
Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/auth', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('soal.pilih-tes');
    }

    return redirect()->route('login')->with('error', 'Email atau password salah.');
})->name('login.submit');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// ======= Import Soal (JSON -> DB) =======
// (akses ini biasanya untuk admin/dev; pertimbangkan middleware auth)
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


// ======= Pilih Tes / Mulai Tes (flow web) =======
// Halaman pilih tes (daftar): tampilkan halaman untuk memilih tes
Route::get('/soal', [SoalController::class, 'pilihTes'])->name('soal.pilih-tes');

// Submit dari form "Mulai Tes" (simpan pilihan di session lalu redirect ke mulai)
Route::post('/soal/mulai', [SoalController::class, 'prepareTes'])->name('soal.start');

// Halaman ujian sebenarnya: mulaiTes menerima {id} -> named 'soal.index' supaya prepareTes redirect konsisten
Route::get('/soal/mulai/{id}', [SoalController::class, 'mulaiTes'])->name('soal.index');


// ======= API untuk AJAX (dipanggil dari frontend JS) =======
// Ambil soal berdasarkan nomor
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// Simpan jawaban sementara via AJAX (dipakai per-part atau auto-save)
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// Submit final / auto-submit (misal endpoint waktu habis)
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');


// ======= Koreksi, History & Results =======
// Koreksi akhir & simpan hasil via web form
Route::post('/soal/store', [SoalController::class, 'store'])->name('soal.store');

// History dan detail hasil
Route::get('/history', [SoalController::class, 'history'])->name('soal.history');
Route::get('/history/{id}', [SoalController::class, 'historyDetail'])->name('soal.results');

// Halaman selesai test (dipakai redirect di controller)
Route::get('/tes/selesai', function () { 
    return view('tes.selesai'); // buat view ini: resources/views/tes/selesai.blade.php
})->name('tes.selesai');
