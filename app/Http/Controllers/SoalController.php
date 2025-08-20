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
use Illuminate\Support\Facades\Storage;
class SoalController extends Controller
{

    public function home()
    {
        $packets = Packet::all(); // ambil semua packet
        return view('home', compact('packets'));
}
public function SoalAdaptifAnalogi()
{
    $path = public_path('assets/soal/soal_adaptif_analogi.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 1; // Sesuaikan jika perlu

  foreach ($data['soal'] as $index => $soal) {
    $deskripsiLengkap = [
        'soal' => $soal,
        'option_a' => $data['option_a'][$index] ?? null,
        'option_b' => $data['option_b'][$index] ?? null,
        'option_c' => $data['option_c'][$index] ?? null,
        'option_d' => $data['option_d'][$index] ?? null,
        'option_e' => $data['option_e'][$index] ?? null,
    ];

    Question::create([
        'packet_id'   => $packetId,
        'number'      => $index + 1,
        'description' => json_encode($deskripsiLengkap),
        'is_example'  => 0,
    ]);
}
    return "Import selesai!";

}
public function SoalAdaptifPenalaran()
{
    $path = public_path('assets/soal/soal_adaptif_penalaran.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 2; // Sesuaikan jika perlu

  foreach ($data['soal'] as $index => $soal) {
    $deskripsiLengkap = [
        'soal' => $soal,
        'option_a' => $data['option_a'][$index] ?? null,
        'option_b' => $data['option_b'][$index] ?? null,
        'option_c' => $data['option_c'][$index] ?? null,
        'option_d' => $data['option_d'][$index] ?? null,
        'option_e' => $data['option_e'][$index] ?? null,
    ];

    Question::create([
        'packet_id'   => $packetId,
        'number'      => $index + 1,
        'description' => json_encode($deskripsiLengkap),
        'is_example'  => 0,
    ]);
}
    return "Import selesai!";

}

public function SoalAdaptifSpasial3D()
{
    $path = public_path('assets/soal/soal_adaptif_spasial_3d.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 3; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalAdaptifSpasial()
{
    $path = public_path('assets/soal/soal_adaptif_spasial.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 4; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalAkurasi()
{
    $path = public_path('assets/soal/soal_akurasi.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 5; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal'     => $data['soal'][$index] ?? null,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}

public function SoalPenalaran()
{
    $path = public_path('assets/soal/soal_penalaran.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 6; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
            'option_f' => $data['option_f'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalHollandRiasec()
{
    $path = public_path('assets/soal/soal_holland_riasec.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 8; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalSpasial()
{
    $path = public_path('assets/soal/soal_spasial.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 7; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
            'option_f' => $data['option_f'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalSpasial3D()
{
    $path = public_path('assets/soal/soal_spasial_3d.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 9; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
            'option_e' => $data['option_e'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
        ]);
    }

    return "Import selesai!";
}
public function SoalToeic()
{
    $path = public_path('assets/soal/soal_toeic.json');

    if (!file_exists($path)) return 'File tidak ditemukan.';

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    if (!isset($data['soal']) || !is_array($data['soal'])) {
        return 'Format JSON salah. Key "soal" tidak ditemukan atau bukan array.';
    }

    $packetId = 10; // Ganti sesuai kebutuhan

    foreach ($data['soal'] as $index => $soal) {
        $deskripsiLengkap = [
            'soal' => $soal,
            'option_a' => $data['option_a'][$index] ?? null,
            'option_b' => $data['option_b'][$index] ?? null,
            'option_c' => $data['option_c'][$index] ?? null,
            'option_d' => $data['option_d'][$index] ?? null,
        ];

        Question::create([
            'packet_id'   => $packetId,
            'number'      => $index + 1,
            'description' => json_encode($deskripsiLengkap),
            'is_example'  => 0,
            
        ]);
    }

    return "Import selesai!";
}
public function prepareTes(Request $request)
{
    $packetId = $request->input('packet_id');

    // validasi packet_id
    $packet = Packet::find($packetId);
    if (!$packet) {
        return redirect()->route('home')
                         ->with('error', 'Paket tes tidak valid.');
    }

    // simpan session sekali pakai
    session(['selected_packet_id' => $packetId]);

    // redirect ke mulaiTes
    return redirect()->route('soal.index', ['packetId' => $packetId]);
}
public function soalStart(Request $request)
{
    $request->validate([
        'packet_id' => 'required|exists:packets,id'
    ]);

    // Simpan ke session
    session(['packet_id' => $request->packet_id]);

    return redirect()->route('soal.index');
}


    // Step 2: tampilkan soal
    public function mulaiTes()
{
    // 🔹 Ambil packet_id dari session
    $packetId = session('packet_id');

    // Kalau nggak ada, redirect balik
    if (!$packetId) {
        return redirect()->route('home')->with('error', 'Silakan pilih paket tes dulu.');
    }

    // 🔹 Cari packet
    $packet = Packet::find($packetId);
    if (!$packet) {
        return view('soal.error', ['message' => 'Paket tidak ditemukan.']);
    }

    // 🔹 Cari test yang sesuai
    $test = Test::find($packet->test_id);
    if (!$test) {
        return view('soal.error', ['message' => 'Tes tidak ditemukan.']);
    }

    // 🔹 Ambil semua soal dari packet
    $soal = Question::where('packet_id', $packet->id)->get();
    $jumlah_soal = $soal->count();

    // 🔹 Render view
    return view('soal.index', [
        'test_id'     => $test->id,
        'soal'        => $soal,
        'selection'   => null,
        'path'        => $test->code,
        'packet_id'   => $packet->id,
        'packet'      => $packet,
        'test'        => $test,
        'jumlah_soal' => $jumlah_soal,
        'part'        => $packet->part
    ]);
}




    // API: Ambil satu soal berdasarkan nomor
    public function getSoal($test_id, $packet_id, $number)
    {
        Log::info('Memuat soal ke-', compact('test_id','packet_id','number'));
    
        $question = Question::where('packet_id', $packet_id)
            ->where('number', $number)
            ->first();
    
        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }
    
        // base folder berdasarkan packet id
        $baseFolder = "assets/images/{$packet_id}";
    
        // decode description
        $desc = is_string($question->description) && $this->isJson($question->description)
            ? json_decode($question->description, true)
            : ['soal' => $question->description, 'type' => $question->type];
    
        $looksLikeImage = function($v) {
            return is_string($v) && preg_match('/\.(png|jpe?g|gif)$/i', trim($v));
        };
    
        // soal bisa berisi banyak gambar dipisah ":" atau teks
        $questionImages = [];
        $questionText = null;
        if (!empty($desc['soal'])) {
            $soalRaw = $desc['soal'];
            if (strpos($soalRaw, ':') !== false) {
                $parts = array_map('trim', explode(':', $soalRaw));
                foreach ($parts as $p) {
                    if ($looksLikeImage($p)) {
                        $questionImages[] = asset("{$baseFolder}/" . trim($p));
                    } else {
                        $questionText = trim(($questionText ? $questionText . ' ' : '') . $p);
                    }
                }
            } else {
                if ($looksLikeImage($soalRaw)) {
                    $questionImages[] = asset("{$baseFolder}/" . trim($soalRaw));
                } else {
                    $questionText = $soalRaw;
                }
            }
        }
    
        // opsi dari JSON description (buat URL kalau image)
        $options = [];
        foreach (['a','b','c','d','e','f'] as $code) {
            $key = 'option_' . $code;
            if (!isset($desc[$key])) continue;
            $val = trim($desc[$key]);
            if ($looksLikeImage($val)) {
                $options[] = [
                    'value' => strtoupper($code),
                    'text'  => null,
                    'image' => asset("{$baseFolder}/" . $val),
                ];
            } else {
                $options[] = [
                    'value' => strtoupper($code),
                    'text'  => $val,
                    'image' => null,
                ];
            }
        }
    
        return response()->json([
            'number' => $question->number,
            'questionText'  => $questionText,
            'questionImage' => count($questionImages) ? $questionImages[0] : null,
            'questionImages' => $questionImages,
            'multiSelect' => ($desc['type'] ?? $question->type ?? 'radio') === 'checkbox',
            'selection' => null,
            'path' => (string)$packet_id, // opsional, kalau frontend butuh
            'options' => $options,
        ]);
    }
    

/**
 * Cek apakah string adalah JSON valid
 */
private function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

// Ambil hasil tes (result)
    public function getResult($test_id)
{
    $userId = Auth::id();

    $result = Result::where('user_id', $userId)
                    ->where('test_id', $test_id)
                    ->first();

    if (!$result) {
        return response()->json(['error' => 'Belum ada hasil tes.']);
    }

    return response()->json([
        'score' => $result->score,
        'total_correct' => $result->total_correct,
        'total_wrong' => $result->total_wrong,
        'total_question' => $result->total_question,
    ]);
}
// Simpan Jawaban

// Method simpanJawaban yang sudah diperbaiki


// Method simpanJawaban yang sudah diperbaiki
public function simpanJawaban(Request $request)
{  
    dd($request->all());
    try {
        // ✅ Validasi input
        $request->validate([
            'test_id' => 'required|integer|exists:tests,id',
            'packet_id' => 'required|integer|exists:packets,id', 
            'part' => 'required|integer',
            'answers' => 'required|array'
        ]);

        $userId   = 1;
        $packetId = $request->packet_id;
        $testId   = $request->test_id;
        $part     = $request->part;
        $answers  = $request->input('answers', []);

        // ✅ Ambil semua soal di packet ini (tanpa test_id dan part yang tidak ada di migration)
        $questions = Question::where('packet_id', $packetId)->get();
        
        if ($questions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Soal tidak ditemukan untuk packet ini.'
            ], 404);
        }

        // ✅ Proses jawaban user
        $finalAnswers = [];
        $unanswered = [];
        
        foreach ($questions as $question) {
            $number = $question->number;
            
            if (!isset($answers[$number]) || empty($answers[$number])) {
                $finalAnswers[$number] = null;
                $unanswered[] = $number;
            } else {
                // Pastikan jawaban dalam format array untuk konsistensi
                $finalAnswers[$number] = is_array($answers[$number]) 
                    ? $answers[$number] 
                    : [$answers[$number]];
            }
        }

        // ✅ Cek apakah semua soal sudah dijawab
        // if (count($unanswered) > 0) {
        //     return response()->json([
        //         'status' => 'belum_lengkap',
        //         'message' => 'Harap mengisi semua soal untuk melakukan submit.',
        //         'unanswered' => $unanswered
        //     ], 422);
        // }

        // ✅ Simpan ke test_temporary (update atau create)
        TestTemporary::updateOrCreate(
            [
                'user_id' => $userId,
                'test_id' => $testId,
                'packet_id' => $packetId,
                'part' => $part,
            ],
            [
                'json' => json_encode($finalAnswers),
                'result_temp' => null,
            ]
        );

        // ✅ Cek apakah ada part selanjutnya
        $nextPacket = Packet::where('test_id', $testId)
                            ->where('part', '>', $part)
                            ->orderBy('part')
                            ->first();

        if ($nextPacket) {
            return response()->json([
                'status' => 'lanjut',
                'message' => 'Part berhasil disimpan. Lanjut ke part berikutnya.',
                'next_part' => $nextPacket->part,
                'next_packet_id' => $nextPacket->id,
            ]);
        }

        // ✅ Tidak ada part lagi, hitung skor final
        return $this->calculateFinalScore($userId, $testId);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Error in simpanJawaban: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'test_id' => $request->test_id ?? null,
            'packet_id' => $request->packet_id ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan server. Silakan coba lagi.',
            'debug' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
    // ✅ Tandai bahwa user sudah menyelesaikan tes


}

// ✅ Method terpisah untuk menghitung skor final
private function calculateFinalScore($userId, $testId)
{
    // dd(request()->all());
    try {
        Log::info('calculateFinalScore called', [
            'user_id' => $userId,
            'test_id' => $testId
        ]);

        $temps = TestTemporary::where('user_id', $userId)
                    ->where('test_id', $testId)
                    ->orderBy('packet_id')
                    ->get();

        if ($temps->isEmpty()) {
            Log::error('No temporary data found', compact('userId','testId'));
            return redirect()->route('home')->with('error', 'Data jawaban tidak ditemukan.');
        }

        // Kelompokkan per packet_id
        $byPacket = $temps->groupBy('packet_id');

        // Buat agregat untuk ditampilkan di view (total semua packet)
        $grandTotalQuestions = 0;
        $grandTotalCorrect   = 0;

        foreach ($byPacket as $packetId => $rows) {
            if (empty($packetId)) {
                Log::warning('Found temporary row without packet_id', [
                    'user_id' => $userId,
                    'test_id' => $testId
                ]);
                continue; // lewati yang packet_id nya null
            }

            $answers = [];
            $totalQuestions = 0;
            $score = 0;

            foreach ($rows as $temp) {
                $partAnswers = json_decode($temp->json, true) ?: [];
                foreach ($partAnswers as $number => $userAnswer) {
                    // simpan jawaban terakhir untuk nomor yang sama
                    $answers[$number] = $userAnswer;
                    $totalQuestions++;

                    $correctAnswer = $this->getCorrectAnswer($packetId, $number);
                    if ($correctAnswer && $this->isAnswerCorrect($userAnswer, $correctAnswer)) {
                        $score++;
                    }
                }
            }

            // Selalu INSERT baris baru (history per attempt)
            // Pastikan model Result mengizinkan mass-assign (lihat catatan di bawah)
            Result::create([
                'user_id' => $userId,
                'test_id' => $testId,
                'packet_id' => $packetId,
                'json' => json_encode([
                    'answers'        => $answers,
                    'score'          => $score,
                    'total_correct'  => $score,
                    'total_wrong'    => $totalQuestions - $score,
                    'total_question' => $totalQuestions,
                ]),
            ]);

            $grandTotalQuestions += $totalQuestions;
            $grandTotalCorrect   += $score;
        }

        // Bersihkan temporary jawaban
        TestTemporary::where('user_id', $userId)
            ->where('test_id', $testId)
            ->delete();

        // Data untuk view (agregat semua packet)
        $finalScorePercent = $grandTotalQuestions > 0
            ? round(($grandTotalCorrect / $grandTotalQuestions) * 100)
            : 0;

        return view('soal.hasil', [
            'status'  => 'selesai',
            'message' => 'Test berhasil diselesaikan!',
            'result'  => [
                'score'          => $finalScorePercent,
                'total_correct'  => $grandTotalCorrect,
                'total_wrong'    => $grandTotalQuestions - $grandTotalCorrect,
                'total_question' => $grandTotalQuestions,
            ],
            'redirect' => route('home'),
        ]);

    } catch (\Throwable $e) { // pakai Throwable biar error non-Exception juga ketangkap
        Log::error('Error in calculateFinalScore: ' . $e->getMessage(), [
            'user_id' => $userId,
            'test_id' => $testId,
            'trace'   => $e->getTraceAsString(),
        ]);

        return redirect()->route('home')->with('error', 'Gagal menghitung skor final.');
    }
}



private function getCorrectAnswer($packetId, $questionNumber)
{
    // Contoh hardcoded sementara
    $answerKeys = [
        1 => [ // packet_id = 1
            1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'A',
            6 => 'B', 7 => 'C', 8 => 'D', 9 => 'A', 10 => 'B',
            11 => 'C', 12 => 'D', 13 => 'A', 14 => 'B', 15 => 'C',
            16 => 'D', 17 => 'A', 18 => 'B', 19 => 'C', 20 => 'D',
            21 => 'A', 22 => 'B', 23 => 'C', 24 => 'D', 25 => 'A',
            26 => 'B'
        ],
        // Tambahkan packet lain jika perlu
    ];

    return $answerKeys[$packetId][$questionNumber] ?? null;
}

private function isAnswerCorrect($userAnswer, $correctAnswer)
{
    $userAnswerArray = is_array($userAnswer) ? $userAnswer : [$userAnswer];
    $correctAnswerArray = is_array($correctAnswer) ? $correctAnswer : [$correctAnswer];

    sort($userAnswerArray);
    sort($correctAnswerArray);

    return $userAnswerArray === $correctAnswerArray;
}
}

