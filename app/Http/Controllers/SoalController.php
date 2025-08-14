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
    // ==== IMPORTER METHODS (tetap utuh — dari teman) ====
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

    // ==== COMMON PAGE / FLOW METHODS (gabungan dari keduanya) ====

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

    // Tampilkan daftar tes
    public function pilihTes()
    {
        $tests = Test::with('packets')->get();
        return view('soal.pilih-tes', compact('tests'));
    }

    public function historyDetail($id)
    {
        $result = Result::findOrFail($id);

        // Kirim ke results.blade.php
        return view('soal.results', [
            'benar' => $result->score, // Asumsi score adalah jumlah benar
            'salah' => 0, // Tidak ada data salah di sini
            'kosong' => 0, // Tidak ada data kosong di sini
            'score' => $result->score,
            'test' => $result->test,
            'timeTaken' => $result->time_taken ?? null, // Pastikan kolom ini ada di tabel results
        ]);
    }

    public function history()
    {
        $results = Result::with(['test', 'packet'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('soal.history', compact('results'));
    }

    public function prepareTes(Request $request)
    {
        $testId = $request->input('test_id');

        if (!$testId || !Test::find($testId)) {
            return redirect()->route('soal.pilih-tes')->with('error', 'Tes tidak valid.');
        }

        session(['selected_test_id' => $testId]);

        return redirect()->route('soal.index', ['id' => $testId]);
    }

    public function mulaiTes($id)
    {
        // Cegah akses langsung
        if (!session()->has('selected_test_id')) {
            return redirect()->route('soal.pilih-tes')->with('error', 'Silakan pilih tes terlebih dahulu.');
        }

        $testId = session('selected_test_id');
        session()->forget('selected_test_id'); // Sekali pakai, hapus langsung

        $test = Test::find($id);
        if (!$test) {
            return view('soal.error', ['message' => 'Tes tidak ditemukan.']);
        }

        $packet = Packet::where('test_id', $test->id)->first();
        if (!$packet) {
            return view('soal.error', ['message' => 'Paket soal belum tersedia untuk tes ini.']);
        }

        $jumlah_soal = Question::where('packet_id', $packet->id)->count();

        return view('soal.index', [
            'soal' => [],
            'selection' => null,
            'path' => $test->code,
            'packet' => $packet,
            'test' => $test,
            'jumlah_soal' => $jumlah_soal,
            'part' => $packet->part
        ]);
    }

    // ==== API: Ambil satu soal berdasarkan nomor (digabung & mendukung 2 format) ====
    public function getSoal($test_id, $packet_id, $number)
    {
        Log::info('Debug getSoal() menerima:', [
            'test_id' => $test_id,
            'packet_id' => $packet_id,
            'number' => $number,
        ]);

        // Coba ambil question (dengan options relation jika ada)
        $question = Question::with('options')->where('packet_id', $packet_id)->where('number', $number)->first();

        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }

        $test = Test::find($test_id);
        $pathFromCode = $test ? $test->code : null;

        // Dua kemungkinan penyimpanan:
        // 1) description berisi JSON (format temanmu)
        // 2) question->options relation (format kamu)
        $desc = null;
        if ($question->description) {
            $maybe = json_decode($question->description, true);
            $desc = is_array($maybe) ? $maybe : null;
        }

        // Fungsi bantu: cek apakah string adalah path gambar
        $isImage = fn($value) => is_string($value) && preg_match('/\.(png|jpg|jpeg)$/i', $value);

        // Deteksi soal: teks atau gambar
        $questionText = null;
        $questionImage = null;
        if ($desc && isset($desc['soal'])) {
            if ($isImage($desc['soal'])) {
                $questionImage = $desc['soal'];
            } else {
                $questionText = $desc['soal'];
            }
        } else {
            // fallback: jika description bukan JSON, gunakan description sebagai teks
            if (!$desc && $question->description) {
                $questionText = $question->description;
            }
            // atau jika table question menyimpan image kolom
            if (!$questionImage && isset($question->image)) {
                $questionImage = $question->image;
            }
        }

        // Bangun daftar options:
        $options = [];

        // Prioritaskan relation options jika ada
        if ($question->relationLoaded('options') && $question->options->count() > 0) {
            foreach ($question->options as $opt) {
                $options[] = [
                    'value' => $opt->value,
                    'text' => $opt->text,
                    'image' => $opt->image,
                ];
            }
        } elseif ($desc) {
            // Ambil dari desc option_a..option_f
            foreach (['a', 'b', 'c', 'd', 'e', 'f'] as $code) {
                $key = 'option_' . $code;
                if (!isset($desc[$key])) continue;
                $val = $desc[$key];
                $options[] = [
                    'value' => strtoupper($code),
                    'text' => $isImage($val) ? null : $val,
                    'image' => $isImage($val) ? $val : null
                ];
            }
        }

        // Tentukan multiSelect
        $multiSelect = false;
        if ($desc && isset($desc['type'])) {
            $multiSelect = $desc['type'] === 'checkbox';
        } else {
            $multiSelect = isset($question->type) && $question->type === 'checkbox';
        }

        return response()->json([
            'number'        => $question->number,
            'questionText'  => $questionText,
            'questionImage' => $questionImage,
            'multiSelect'   => $multiSelect,
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

        // Jika kolom total_correct/dst tidak ada, coba parse dari json
        $total_correct = $result->total_correct ?? null;
        $total_wrong = $result->total_wrong ?? null;
        $total_question = $result->total_question ?? null;

        if (is_null($total_correct) && $result->json) {
            $decoded = json_decode($result->json, true);
            $total_correct = $decoded['score'] ?? $decoded['total_correct'] ?? null;
            $total_wrong = $decoded['total_wrong'] ?? null;
            $total_question = $decoded['total_question'] ?? null;
        }

        return response()->json([
            'score' => $result->score,
            'total_correct' => $total_correct,
            'total_wrong' => $total_wrong,
            'total_question' => $total_question,
        ]);
    }

    // ==== Simpan Jawaban (digabung / versi final yang robust) ====
    public function simpanJawaban(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'test_id' => 'required|integer|exists:tests,id',
                'packet_id' => 'required|integer|exists:packets,id',
                'part' => 'required|integer',
                'answers' => 'required|array'
            ]);

            // gunakan Auth-> fallback ke 1 (sesuai request awal)
            $userId = Auth::id() ?? 1;
            $packetId = $request->packet_id;
            $testId = $request->test_id;
            $part = $request->part;
            $answers = $request->input('answers', []);

            // Ambil semua soal di packet ini
            $questions = Question::where('packet_id', $packetId)->get();

            if ($questions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Soal tidak ditemukan untuk packet ini.'
                ], 404);
            }

            // Proses jawaban user
            $finalAnswers = [];
            $unanswered = [];

            foreach ($questions as $question) {
                $number = $question->number;

                if (!isset($answers[$number]) || empty($answers[$number])) {
                    $finalAnswers[$number] = null;
                    $unanswered[] = $number;
                } else {
                    $finalAnswers[$number] = is_array($answers[$number]) ? $answers[$number] : [$answers[$number]];
                }
            }

            // Cek apakah semua soal sudah dijawab
            if (count($unanswered) > 0) {
                return response()->json([
                    'status' => 'belum_lengkap',
                    'message' => 'Harap mengisi semua soal untuk melakukan submit.',
                    'unanswered' => $unanswered
                ], 422);
            }

            // Simpan ke test_temporary (updateOrCreate)
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

            // Cek part berikutnya
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

            // Tidak ada part lagi, hitung final
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

    // Method terpisah untuk menghitung skor final (gabungan)
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

            // Simpan ke table results. Simpan juga kolom tambahan jika ada.
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
                    ]),
                    // bila kolom score/total_* ada di migration, set juga:
                    'score' => round(($totalCorrect / max(1, $totalQuestions)) * 100),
                    'total_correct' => $totalCorrect,
                    'total_wrong' => $totalWrong,
                    'total_question' => $totalQuestions
                ]
            );

            Log::info('Result saved:', ['result_id' => $result->id]);

            // hapus temporary
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

    // getCorrectAnswer: sekarang coba ambil dari table options dulu, kalau tidak ada pakai hardcoded fallback
    private function getCorrectAnswer($packetId, $questionNumber)
    {
        // Cari question id berdasarkan packet_id & number
        $question = Question::where('packet_id', $packetId)->where('number', $questionNumber)->first();
        if ($question) {
            $correctOpt = $question->options()->where('is_correct', true)->first();
            if ($correctOpt) {
                return $correctOpt->value;
            }
        }

        // Contoh hardcoded sementara (fallback)
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

    // Koreksi akhir & tampilkan hasil (web)
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

        // Ambil waktu yang digunakan (dikirim dari JS)
        $timeTaken = $request->input('time_taken'); // dalam detik atau menit

        // Simpan hasil
        Result::updateOrCreate(
            ['user_id' => $userId, 'test_id' => $request->input('test_id')],
            [
                'json' => json_encode($answers),
                'score' => $score,
                'time_taken' => $timeTaken // pastikan kolom ini ada di tabel results
            ]
        );

        // Reset timer di sisi sessionStorage (frontend nanti yang hapus)
        session()->forget('quiz_timer_' . $request->input('test_id'));

        $test = Test::find($request->input('test_id'));

        return view('soal.results', compact('benar', 'salah', 'kosong', 'score', 'test', 'timeTaken'));
    }
}
