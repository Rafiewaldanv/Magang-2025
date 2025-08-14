<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;

// 🧪 API route untuk ambil soal per nomor
Route::get('/soal/{test_id}/{packet_id}/{number}', [SoalController::class, 'getSoal'])->name('soal.ajax');

// 📮 API route untuk simpan jawaban (tanpa butuh CSRF)
Route::post('/soal/simpan-jawaban', [SoalController::class, 'simpanJawaban']);
