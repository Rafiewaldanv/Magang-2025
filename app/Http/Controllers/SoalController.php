<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;
use App\Models\TestTemporary;
use App\Models\Packet;
use App\Models\Result;
use App\Models\Test; // ✅ Tambahkan import model Test
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class SoalController extends Controller

{
    // Menampilkan seluruh soal (jika non-AJAX)
    public function index()
{
    $test = Test::find(1); // Ganti '1' dengan ID yang pasti ada
    if (!$test) {
        abort(404, 'Data test tidak ditemukan.');
    }

    $packet = Packet::where('test_id', $test->id)->first();
    if (!$packet) {
        abort(404, 'Data packet tidak ditemukan.');
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



    // ✅ API: Ambil 1 soal berdasarkan nomor & packet
    public function getSoal($test_id, $packet_id, $number)
    {
        // ✅ Logging parameter yang masuk
        Log::info('Debug getSoal() menerima:', [
            'test_id' => $test_id,
            'packet_id' => $packet_id,
            'number' => $number,
        ]);

        $question = Question::where('packet_id', $packet_id)
                            ->where('number', $number)
                            ->first();

        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }

        // Ambil kode path dari tabel tests
        $test = Test::find($test_id);
        $pathFromCode = $test ? $test->code : null;

        $formatted = [
            'number' => $question->number,
            'questionText' => $question->description,
            'questionImage' => $question->image,
            'multiSelect' => $question->type === 'checkbox',
            'selection' => null, // ✅ diset null default
            'path' => $pathFromCode, // ✅ ambil dari field code di tests
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

    // ✅ Submit jawaban dari frontend (AJAX)
    public function simpanJawaban(Request $request)
    {
        $answers = $request->input('answers');
        $userId = Auth::id();
        $packetId = $request->packet_id;
        $testId = $request->test_id;
        $part = $request->part;

        // Hindari duplikasi simpan
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
                'json' => json_encode($answers),
                'result_temp' => count($answers),
            ]);
        }

        // Cek apakah ini part terakhir
        $isLast = !Packet::where('test_id', $testId)->where('part', '>', $part)->exists();

        if ($isLast) {
            Result::create([
                'user_id' => $userId,
                'test_id' => $testId,
                'json' => json_encode($answers),
                'score' => count($answers),
            ]);
        }

        return response()->json(['status' => 'berhasil']);
    }
}
