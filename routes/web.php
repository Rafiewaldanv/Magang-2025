<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


// 🏠 Halaman default
Route::get('/', function () {
    return view('home');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::post('/auth', function (Request $request) {
    $credentials = $request->only('email', 'password'); // ✅ BENAR

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('soal.pilih-tes'); // ganti sesuai rute kamu
    }

    return redirect()->route('login')->with('error', 'Email atau password salah.');
})->name('login.submit');

// Home


// Pilih Tes
Route::get('/soal', [SoalController::class, 'pilihTes'])->name('soal.pilih-tes');
// Submit dari tombol "Mulai Tes"
Route::post('/soal/mulai', [SoalController::class, 'prepareTes'])->name('soal.start');

// Halaman ujian sebenarnya
Route::get('/soal/mulai/{id}', [SoalController::class, 'mulaiTes'])->name('soal.index');



// API Ambil Soal
Route::get('/api/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// Simpan Jawaban via AJAX
Route::post('/soal/simpan', [SoalController::class, 'simpanJawaban'])->name('soal.simpan');

// Submit Jawaban Final
Route::post('/tes/{path}/submit', [SoalController::class, 'simpanJawaban'])->name('tes.submit');

// Koreksi dan Simpan Skor
Route::post('/soal/store', [SoalController::class, 'store'])->name('soal.store');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
