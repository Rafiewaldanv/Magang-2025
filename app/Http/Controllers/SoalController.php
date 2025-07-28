<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;
use App\Models\TestTemporary;
use App\Models\Packet;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SoalController extends Controller
{
    // Menampilkan seluruh soal
    

    public function index()
{
    $test = Test::find(1); // Ganti 1 sesuai kebutuhan

    if (!$test) {
        return view('soal.error', ['message' => 'Soal kamu belum ditemukan (data tes tidak ditemukan).']);
    }

    $packet = Packet::where('test_id', $test->id)->first();
    if (!$packet) {
        return view('soal.error', ['message' => 'Soal kamu belum ditentukan (data paket tidak ditemukan).']);
    }

    $soal = Question::where('packet_id', $packet->id)->get();
    $jumlah_soal = $soal->count();
    $part = $packet->part;
    $path = $test->code;
    $selection = null;

    return view('soal.index', compact(
        'soal', 'selection', 'path',
        'packet', 'test', 'jumlah_soal', 'part'
    ));
}


    // API: Ambil satu soal berdasarkan nomor
    public function getSoal($test_id, $packet_id, $number)
    {
        Log::info('Debug getSoal() menerima:', [
            'test_id' => $test_id,
            'packet_id' => $packet_id,
            'number' => $number,
        ]);
    
        $question = Question::with('options')
            ->where('packet_id', $packet_id)
            ->where('number', $number)
            ->first();
    
        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }
    
        $test = Test::find($test_id);
        $pathFromCode = $test ? $test->code : null;
    
        $formatted = [
            'number' => $question->number,
            'questionText' => $question->description,
            'questionImage' => $question->image,
            'multiSelect' => $question->type === 'checkbox',
            'selection' => null,
            'path' => $pathFromCode,
            'options' => $question->options->map(function ($opt) {
                return [
                    'value' => $opt->code,
                    'text' => $opt->text,
                    'image' => $opt->image,
                ];
            })->toArray(),
        ];
    
        return response()->json($formatted);
    }
    

    // Simpan jawaban (submit akhir)
    public function simpanJawaban(Request $request)
    {
        $answers = $request->input('answers'); // format: [1 => 'A', 2 => 'B', ...]
        $jumlahSoal = $request->input('jumlah_soal');

        // Filter hanya yang dijawab
        $filteredAnswers = array_filter($answers, function ($value) {
            return !is_null($value) && $value !== '';
        });

        // Validasi: semua soal harus terjawab
        if (count($filteredAnswers) < $jumlahSoal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Masih ada soal yang belum dijawab.'
            ], 400);
        }

        $userId = Auth::id();
        $packetId = $request->packet_id;
        $testId = $request->test_id;
        $part = $request->part;

        // Hindari duplikasi penyimpanan
        $existing = TestTemporary::where([
            'user_id' => $userId,
            'test_id' => $testId,
            'packet_id' => $packetId,
            'part' => $part,
        ])->first();

        if (!$existing) {
            TestTemporary::create([
                'user_id' => $userId,
                'test_id' => $testId,
                'packet_id' => $packetId,
                'part' => $part,
                'json' => json_encode($filteredAnswers),
                'result_temp' => count($filteredAnswers),
            ]);
        }

        // Cek apakah part terakhir
        $isLast = !Packet::where('test_id', $testId)
                         ->where('part', '>', $part)
                         ->exists();

        if ($isLast) {
            Result::create([
                'user_id' => $userId,
                'test_id' => $testId,
                'json' => json_encode($filteredAnswers),
                'score' => count($filteredAnswers),
            ]);
        }

        return response()->json(['status' => 'berhasil']);
    }
}
