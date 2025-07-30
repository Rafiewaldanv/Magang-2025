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
            'name' => 'Dummy User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        // Test
        $test = Test::create([
            'name' => 'Tes Logika',
            'code' => 'LOG123',
            'num_order' => 1,
        ]);

        // Packet
        $packet = Packet::create([
            'test_id' => $test->id,
            'part' => 'A',
            'name' => 'Bagian A',
            'description' => 'Soal dasar logika',
            'type' => 'PG',
            'amount' => 2,
            'status' => 'active',
        ]);

        // Questions & Options
        for ($i = 1; $i <= 2; $i++) {
            $question = Question::create([
                'packet_id' => $packet->id,
                'number' => $i,
                'description' => "Pertanyaan ke-$i",
                'is_example' => false,
            ]);

            Option::create([
                'question_id' => $question->id,
                'text' => 'Jawaban A',
                'is_correct' => false,
            ]);

            Option::create([
                'question_id' => $question->id,
                'text' => 'Jawaban B',
                'is_correct' => true,
            ]);

            Option::create([
                'question_id' => $question->id,
                'text' => 'Jawaban C',
                'is_correct' => false,
            ]);

            Option::create([
                'question_id' => $question->id,
                'text' => 'Jawaban D',
                'is_correct' => false,
            ]);
        }

        // Result
        Result::create([
            'user_id' => $user->id,
            'company_id' => 1,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'result' => 80,
        ]);

        // TestTemporary
        TestTemporary::create([
            'id_user' => $user->id,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'part' => 'A',
            'json' => json_encode(['1' => 'B', '2' => 'B']),
            'result_temp' => 2,
        ]);
    }
}
