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
    // Menampilkan seluruh soal (jika non-AJAX)
    public function index()
    {
        $test = Test::find(1); // Ganti 1 sesuai ID default atau gunakan dynamic jika diperlukan

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

    // API: Ambil satu soal berdasarkan nomor
    public function getSoal($test_id, $packet_id, $number)
{
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

    $test = Test::find($test_id);
    $pathFromCode = $test ? $test->code : null;

    $desc = json_decode($question->description, true);

    // Fungsi bantu: cek apakah string adalah path gambar
    $isImage = fn($value) => is_string($value) && preg_match('/\.(png|jpg|jpeg)$/i', $value);

    // Deteksi soal: teks atau gambar
    $questionText = null;
    $questionImage = null;
    if (isset($desc['soal'])) {
        if ($isImage($desc['soal'])) {
            $questionImage = $desc['soal'];
        } else {
            $questionText = $desc['soal'];
        }
    }

    // Bangun daftar options
    $options = [];
    foreach (['a', 'b', 'c', 'd', 'e', 'f'] as $code) {
        $key = 'option_' . $code;

        if (!isset($desc[$key])) continue;

        $val = $desc[$key];

        $options[] = [
            'value' => strtoupper($code),
            'text'  => $isImage($val) ? null : $val,
            'image' => $isImage($val) ? $val : null
        ];
    }

    return response()->json([
        'number'        => $question->number,
        'questionText'  => $questionText,
        'questionImage' => $questionImage,
        'multiSelect'   => ($desc['type'] ?? 'radio') === 'checkbox',
        'selection'     => null,
        'path'          => $pathFromCode,
        'options'       => $options
    ]);
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
        if (count($unanswered) > 0) {
            return response()->json([
                'status' => 'belum_lengkap',
                'message' => 'Harap mengisi semua soal untuk melakukan submit.',
                'unanswered' => $unanswered
            ], 422);
        }

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
}

// ✅ Method terpisah untuk menghitung skor final
private function calculateFinalScore($userId, $testId)
{
    try {
        Log::info('calculateFinalScore called', [
            'user_id' => $userId,
            'test_id' => $testId
        ]);

        $allTemps = TestTemporary::where([
            'user_id' => $userId,
            'test_id' => $testId
        ])->get();

        if ($allTemps->isEmpty()) {
            Log::error('No temporary data found');
            return response()->json([
                'status' => 'error',
                'message' => 'Data jawaban tidak ditemukan.'
            ], 404);
        }

        $score = 0;
        $totalQuestions = 0;
        $finalAnswers = [];

        foreach ($allTemps as $temp) {
            $partAnswers = json_decode($temp->json, true) ?? [];
            $packetId = $temp->packet_id;

            foreach ($partAnswers as $number => $userAnswer) {
                $finalAnswers[$number] = $userAnswer;
                $totalQuestions++;

                // Hitung skor jika ingin aktifkan
                $correctAnswer = $this->getCorrectAnswer($packetId, $number);
                if ($correctAnswer && $this->isAnswerCorrect($userAnswer, $correctAnswer)) {
                    $score++;
                }
            }
        }

        $totalCorrect = $score;
        $totalWrong = $totalQuestions - $score;

        // ✅ Simpan semua data ke kolom `json` sebagai nested array
        $result = Result::updateOrCreate(
            [
                'user_id' => $userId,
                'test_id' => $testId
            ],
            [
                'packet_id' => null,
                'json' => json_encode([
                    'answers' => $finalAnswers,
                    'score' => $score,
                    'total_correct' => $totalCorrect,
                    'total_wrong' => $totalWrong,
                    'total_question' => $totalQuestions
                ])
            ]
        );

        Log::info('Result saved:', ['result_id' => $result->id]);

        TestTemporary::where([
            'user_id' => $userId,
            'test_id' => $testId
        ])->delete();

        return response()->json([
            'status' => 'selesai',
            'message' => 'Test berhasil diselesaikan!',
            'result' => [
                'score' => $score = round(($totalCorrect / $totalQuestions) * 100),
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
                'total_question' => $totalQuestions
                
            ],
            'redirect' => route('tes.selesai')
        ]);

    } catch (\Exception $e) {
        Log::error('Error in calculateFinalScore: ' . $e->getMessage(), [
            'user_id' => $userId,
            'test_id' => $testId,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menghitung skor final.',
            'debug' => config('app.debug') ? $e->getMessage() : null
        ], 500);
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

