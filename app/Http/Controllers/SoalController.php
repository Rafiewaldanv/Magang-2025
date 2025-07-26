<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function mulaiUjian(Request $request)
    {
        // Bisa simpan session atau apapun nanti
        return redirect()->route('soal.index');
    }

    public function soal()
    {
        return view('soal');
    }

    public function submitJawaban(Request $request)
    {
        // Proses simpan jawaban kalau perlu
        return redirect()->route('soal.selesai');
    }

    public function selesai()
    {
        return view('tes.selesai');
    }
}
