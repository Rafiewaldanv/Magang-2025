<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Test;
use App\Models\Packet;
use App\Models\Question;
use App\Models\Option;
use App\Models\Result;
use App\Models\TestTemporary;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            // ... seeder lain
        ]);

        // User
        $user = User::create([
            'name' => 'Farhan',
            'email' => 'farhan@example.com',
            'password' => Hash::make('password'),
        ]);

        // Test
        $test = Test::create([
            'name' => 'Tes Penalaran Umum',
            'code' => 'PU123',
            'num_order' => 1,
        ]);

        // Packet
        $packet = Packet::create([
            'test_id' => $test->id,
            'part' => 'A',
            'name' => 'Bagian A',
            'description' => 'Soal penalaran umum logika dan analisis',
            'type' => 'PG',
            'amount' => 30,
            'status' => 'active',
        ]);

        // Questions & Options (30 soal)
        $tempAnswers = [];
        for ($i = 1; $i <= 30; $i++) {
            $question = Question::create([
                'packet_id' => $packet->id,
                'number' => $i,
                'description' => "Jika semua A adalah B dan semua B adalah C, maka apakah semua A adalah C? (Soal ke-$i)",
                'is_example' => false,
            ]);

            $options = [
                ['text' => 'Ya, selalu benar', 'is_correct' => false],
                ['text' => 'Tidak, tidak selalu', 'is_correct' => false],
                ['text' => 'Benar jika C adalah A', 'is_correct' => false],
                ['text' => 'Benar, karena hubungan transitif', 'is_correct' => false],
            ];

            $correctIndex = rand(0, 3);
            $options[$correctIndex]['is_correct'] = true;
            $correctLabel = ['A', 'B', 'C', 'D'][$correctIndex];
            $tempAnswers[(string)$i] = $correctLabel;

            foreach ($options as $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $opt['text'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }
        }

        // Result
        Result::create([
            'user_id' => $user->id,
            'company_id' => 1,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'result' => 90,
        ]);

        // TestTemporary
        TestTemporary::create([
            'id_user' => $user->id,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'part' => 'A',
            'json' => json_encode($tempAnswers),
            'result_temp' => count($tempAnswers),
        ]);
    }
}
