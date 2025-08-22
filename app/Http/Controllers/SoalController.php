<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\TestTemporary;
use App\Models\Packet;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;

class SoalController extends Controller
{
    public function home()
    {
        $packets = Packet::all();
        $ongoingTest = null;

        $packetId = session('packet_id');
        if (!empty($packetId)) {
            $packetName = null;
            if (!empty($packets)) {
                $found = $packets->firstWhere('id', $packetId);
                if ($found) {
                    $packetName = $found->name ?? null;
                }
            }

            if (empty($packetName)) {
                try {
                    $pkt = Packet::find($packetId);
                    if ($pkt) $packetName = $pkt->name ?? null;
                } catch (\Throwable $e) {
                }
            }

            if (empty($packetName)) $packetName = 'Paket Tes';

            $ongoingTest = [
                'packet_id'   => $packetId,
                'packet_name' => $packetName,
            ];
        } else {
            if (auth()->check()) {
                $userId = auth()->id();
                $latestTemp = TestTemporary::where('user_id', $userId)->orderBy('created_at','desc')->first();
                if ($latestTemp && $latestTemp->packet_id) {
                    session(['packet_id' => $latestTemp->packet_id]);

                    $packetName = null;
                    if (!empty($packets)) {
                        $found = $packets->firstWhere('id', $latestTemp->packet_id);
                        if ($found) $packetName = $found->name ?? null;
                    }
                    if (empty($packetName)) {
                        try {
                            $pkt = Packet::find($latestTemp->packet_id);
                            if ($pkt) $packetName = $pkt->name ?? null;
                        } catch (\Throwable $e) {
                        }
                    }
                    if (empty($packetName)) $packetName = 'Paket Tes';

                    $ongoingTest = [
                        'packet_id'   => $latestTemp->packet_id,
                        'packet_name' => $packetName,
                    ];
                }
            }
        }

        return view('home', compact('packets', 'ongoingTest'));
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
        $packetId = 1;
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
        $packetId = 2;
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
        $packetId = 3;
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
        $packetId = 4;
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
        $packetId = 5;
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
        $packetId = 6;
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
        $packetId = 8;
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
        $packetId = 7;
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
        $packetId = 9;
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
        $packetId = 10;
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
        $packet = Packet::find($packetId);
        if (!$packet) {
            return redirect()->route('home')
                             ->with('error', 'Paket tes tidak valid.');
        }
        session(['selected_packet_id' => $packetId]);
        return redirect()->route('soal.index', ['packetId' => $packetId]);
    }

    public function soalStart(Request $request)
    {
        $request->validate([
            'packet_id' => 'required|exists:packets,id'
        ]);
        session(['packet_id' => $request->packet_id]);
        session(['test_started_at' => now()->toDateTimeString()]);
        return redirect()->route('soal.index');
    }

    public function mulaiTes()
    {
        $packetId = session('packet_id');
        if (!$packetId) {
            return redirect()->route('home')->with('error', 'Silakan pilih paket tes dulu.');
        }
        $packet = Packet::find($packetId);
        if (!$packet) {
            return view('soal.error', ['message' => 'Paket tidak ditemukan.']);
        }
        $test = Test::find($packet->test_id);
        if (!$test) {
            return view('soal.error', ['message' => 'Tes tidak ditemukan.']);
        }
        $soal = Question::where('packet_id', $packet->id)->get();
        $jumlah_soal = $soal->count();
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

    public function getSoal($test_id, $packet_id, $number)
    {
        $question = Question::where('packet_id', $packet_id)
            ->where('number', $number)
            ->first();

        if (!$question) {
            return response()->json(['error' => 'Soal tidak ditemukan'], 404);
        }

        $baseFolder = "assets/images/{$packet_id}";

        $desc = is_string($question->description) && $this->isJson($question->description)
            ? json_decode($question->description, true)
            : ['soal' => $question->description, 'type' => $question->type];

        $looksLikeImage = function($v) {
            return is_string($v) && preg_match('/\.(png|jpe?g|gif)$/i', trim($v));
        };

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
            'path' => (string)$packet_id,
            'options' => $options,
        ]);
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

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

    public function cancelTest(Request $request)
    {
        try {
            $userId = auth()->id();
            $packetId = $request->input('packet_id');
            session()->forget('packet_id');

            if ($packetId && $userId) {
                TestTemporary::where('user_id', $userId)
                    ->where('packet_id', $packetId)
                    ->delete();
            }

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => 'Gagal membatalkan'], 500);
        }
    }

    public function simpanJawaban(Request $request)
    {
        try {
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

            $questions = Question::where('packet_id', $packetId)->get();

            if ($questions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Soal tidak ditemukan untuk packet ini.'
                ], 404);
            }

            $finalAnswers = [];
            $unanswered = [];

            foreach ($questions as $question) {
                $number = $question->number;
                if (!isset($answers[$number]) || empty($answers[$number])) {
                    $finalAnswers[$number] = null;
                    $unanswered[] = $number;
                } else {
                    $finalAnswers[$number] = is_array($answers[$number])
                        ? $answers[$number]
                        : [$answers[$number]];
                }
            }

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

            return $this->calculateFinalScore($userId, $testId);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.'
            ], 500);
        }
    }

    private function calculateFinalScore($userId, $testId)
    {
        try {
            $temps = TestTemporary::where('user_id', $userId)
                        ->where('test_id', $testId)
                        ->orderBy('packet_id')
                        ->get();

            if ($temps->isEmpty()) {
                return redirect()->route('home')->with('error', 'Data jawaban tidak ditemukan.');
            }

            $byPacket = $temps->groupBy('packet_id');

            $grandTotalQuestions = 0;
            $grandTotalCorrect   = 0;

            $processedPacketIds = [];
            $processedPacketNames = [];

            foreach ($byPacket as $packetId => $rows) {
                if (empty($packetId)) {
                    continue;
                }

                $processedPacketIds[] = $packetId;

                try {
                    $pkt = Packet::find($packetId);
                    $processedPacketNames[$packetId] = $pkt ? $pkt->name : null;
                } catch (\Throwable $e) {
                    $processedPacketNames[$packetId] = null;
                }

                $answers = [];
                $totalQuestions = 0;
                $score = 0;

                foreach ($rows as $temp) {
                    $partAnswers = json_decode($temp->json, true) ?: [];
                    foreach ($partAnswers as $number => $userAnswer) {
                        $answers[$number] = $userAnswer;
                        $totalQuestions++;

                        $correctAnswer = $this->getCorrectAnswer($packetId, $number);
                        if ($correctAnswer && $this->isAnswerCorrect($userAnswer, $correctAnswer)) {
                            $score++;
                        }
                    }
                }

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

            TestTemporary::where('user_id', $userId)
                ->where('test_id', $testId)
                ->delete();

            session()->forget('packet_id');

            $finalScorePercent = $grandTotalQuestions > 0
                ? round(($grandTotalCorrect / $grandTotalQuestions) * 100)
                : 0;

            $firstPacketId = !empty($processedPacketIds) ? $processedPacketIds[0] : null;
            $firstPacketName = null;
            if ($firstPacketId && isset($processedPacketNames[$firstPacketId])) {
                $firstPacketName = $processedPacketNames[$firstPacketId];
            }

            $hasilPayload = [
                'status'  => 'selesai',
                'message' => 'Test berhasil diselesaikan!',
                'result'  => [
                    'score'          => $finalScorePercent,
                    'total_correct'  => $grandTotalCorrect,
                    'total_wrong'    => $grandTotalQuestions - $grandTotalCorrect,
                    'total_question' => $grandTotalQuestions,
                ],
                'redirect' => route('home'),
                'packet_id'   => $firstPacketId,
                'packet_name' => $firstPacketName ?? null,
            ];

            session()->flash('hasil_payload', $hasilPayload);
            session()->flash('processed_packet_ids', $processedPacketIds);
            session()->flash('processed_packet_names', $processedPacketNames);

            if ($firstPacketId) {
                return redirect()->route('soal.hasil', ['packet_id' => $firstPacketId]);
            }

            return redirect()->route('home')->with('message', 'Test selesai, namun tidak ada packet untuk ditampilkan.');

        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', 'Gagal menghitung skor final.');
        }
    }

    public function hasil(Request $request, $packet_id = null)
    {
        $payload = session('hasil_payload');
        $packetIds = session('processed_packet_ids', []);
        $processedNames = session('processed_packet_names', []);

        if (empty($payload)) {
            return redirect()->route('home')->with('error', 'Hasil tidak ditemukan atau sudah kadaluarsa.');
        }

        if ($packet_id && !in_array($packet_id, $packetIds)) {
            return redirect()->route('home')->with('error', 'Packet tidak ditemukan untuk hasil ini.');
        }

        $packetName = $payload['packet_name'] ?? null;
        if (empty($packetName) && $packet_id && !empty($processedNames) && isset($processedNames[$packet_id])) {
            $packetName = $processedNames[$packet_id];
        }
        if (empty($packetName) && $packet_id) {
            try {
                $pkt = Packet::find($packet_id);
                $packetName = $pkt ? $pkt->name : null;
            } catch (\Throwable $e) {
                $packetName = null;
            }
        }

        session()->forget('hasil_payload');
        session()->forget('processed_packet_ids');
        session()->forget('processed_packet_names');

        $payload['packet_name'] = $packetName ?? ($payload['packet_name'] ?? null);

        return view('soal.hasil', array_merge($payload, [
            'packetId' => $packet_id,
            'packetIds' => $packetIds,
        ]));
    }

    private function getCorrectAnswer($packetId, $questionNumber)
    {
        $answerKeys = [
            1 => [
                1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'A',
                6 => 'B', 7 => 'C', 8 => 'D', 9 => 'A', 10 => 'B',
                11 => 'C', 12 => 'D', 13 => 'A', 14 => 'B', 15 => 'C',
                16 => 'D', 17 => 'A', 18 => 'B', 19 => 'C', 20 => 'D',
                21 => 'A', 22 => 'B', 23 => 'C', 24 => 'D', 25 => 'A',
                26 => 'B'
            ],
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
