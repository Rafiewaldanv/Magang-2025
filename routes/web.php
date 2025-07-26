<?php

use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// POST: Mulai ujian -> redirect ke soal
Route::post('/mulai-ujian', function () {
    return redirect()->route('soal');
})->name('mulai-ujian');

// GET: Soal
Route::get('/soal', function () {
    return view('soal');
})->name('soal');

// POST: Submit jawaban -> redirect ke selesai
Route::post('/soal/submit', function () {
    return redirect()->route('tes.selesai');
})->name('soal.submit');

// GET: Tes selesai
Route::get('/tes/selesai', function () {
    return view('tes.selesai');
})->name('tes.selesai');
