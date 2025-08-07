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
    // Halaman awal soal
    public function index()
    {
        $userId = Auth::id() ?? 1;
        $test = Test::first(); // atau pakai session/request jika ada

        if (!$test) {
            return view('soal.error', ['message' => 'Tes tidak ditemukan.']);
        }

        $packet = Packet::where('test_id', $test->id)->first();
        if (!$packet) {
            return view('soal.error', ['message' => 'Paket soal belum tersedia.']);
        }

        $jumlah_soal = Question::where('packet_id', $packet->id)->count();

        return view('soal.index', [
            'soal' => [], // soal akan dimuat via JS
            'selection' => null,
            'path' => $test->code,
            'packet' => $packet,
            'test' => $test,
            'jumlah_soal' => $jumlah_soal,
            'part' => $packet->part
        ]);
    }

    // API: Ambil soal by nomor
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
        return response()->json([
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
        ]);
    }

    // Simpan jawaban (sementara)
    public function simpanJawaban(Request $request)
    {
        $answers = $request->input('answers');
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

        $userId = Auth::id() ?? 1;
        $packetId = $request->packet_id;
        $testId = $request->test_id;
        $part = $request->part;

        // Hindari penyimpanan ganda
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

        // Jika ini bagian terakhir → simpan Result final
        $isLastPart = !Packet::where('test_id', $testId)
                            ->where('part', '>', $part)
                            ->exists();

        if ($isLastPart) {
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

    // Koreksi akhir & tampilkan hasil
    public function store(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $answers = $request->input('pilihan'); // e.g. ['1' => 'A']
        $id_soal = $request->input('id');
        $jumlah = $request->input('jumlah');

        $benar = $salah = $kosong = 0;

        for ($i = 0; $i < $jumlah; $i++) {
            $nomor = $id_soal[$i];
            $jawaban = $answers[$nomor] ?? null;

            $question = Question::with('options')->find($nomor);

            if (!$jawaban) {
                $kosong++;
            } elseif ($question) {
                $kunci = $question->options->where('is_correct', true)->first();
                if ($kunci && $jawaban === $kunci->value) {
                    $benar++;
                } else {
                    $salah++;
                }
            }
        }

        $score = $benar * 10;

        // Simpan hasil (opsional)
        Result::updateOrCreate(
            ['user_id' => $userId, 'test_id' => $request->input('test_id')],
            ['json' => json_encode($answers), 'score' => $score]
        );

        return view('soal.results', compact('benar', 'salah', 'kosong', 'score'));
    }
}
