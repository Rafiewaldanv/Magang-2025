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
    // Menampilkan halaman awal soal interaktif
    public function index()
    {
        $userId = Auth::id();
        $test = Test::first(); // atau bisa pakai session/request

        if (!$test) {
            return view('soal.error', ['message' => 'Tes tidak ditemukan.']);
        }

        $packet = Packet::where('test_id', $test->id)->first();
        if (!$packet) {
            return view('soal.error', ['message' => 'Paket soal belum tersedia.']);
        }

        $jumlah_soal = Question::where('packet_id', $packet->id)->count();

        return view('soal.index', [
            'soal' => [], // Soal akan dimuat via AJAX
            'selection' => null,
            'path' => $test->code,
            'packet' => $packet,
            'test' => $test,
            'jumlah_soal' => $jumlah_soal,
            'part' => $packet->part
        ]);
    }

    // API: Ambil satu soal dan opsinya
    public function getSoal($test_id, $packet_id, $number)
    {
        Log::info('Memuat soal ke-', ['test_id' => $test_id, 'packet_id' => $packet_id, 'number' => $number]);

        $question = Question::with('options')
            ->where('packet_id', $packet_id)
            ->where('number', $number)
            ->first();

        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan.'], 404);
        }

        $test = Test::find($test_id);
        $formatted = [
            'number' => $question->number,
            'questionText' => $question->description,
            'questionImage' => $question->image,
            'multiSelect' => $question->type === 'checkbox',
            'selection' => null,
            'path' => $test ? $test->code : null,
            'options' => $question->options->map(function ($opt) {
                return [
                    'value' => $opt->value,
                    'text' => $opt->text,
                    'image' => $opt->image,
                ];
            })->toArray(),
        ];

        return response()->json($formatted);
    }

    // Simpan jawaban akhir semua soal (dari frontend)
    public function simpanJawaban(Request $request)
    {
        $answers = $request->input('answers'); // format: [1 => 'A', 2 => 'B', ...]
        $jumlahSoal = $request->input('jumlah_soal');

        $filteredAnswers = array_filter($answers, function ($v) {
            return !is_null($v) && $v !== '';
        });

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

        // Cek apakah sudah ada sebelumnya (hindari dobel simpan)
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

        // Cek apakah sudah bagian terakhir
        $isLastPart = !Packet::where('test_id', $testId)
                            ->where('part', '>', $part)
                            ->exists();

        if ($isLastPart) {
            // Simpan hasil akhir
            Result::updateOrCreate(
                ['user_id' => $userId, 'test_id' => $testId],
                [
                    'json' => json_encode($filteredAnswers),
                    'score' => count($filteredAnswers)
                ]
            );
        }

        return response()->json(['status' => 'berhasil']);
    }
}
