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
        ]);

        // User dummy
        $user = User::firstOrCreate([
            'email' => 'farhan@example.com',
        ], [
            'name' => 'Farhan',
            'password' => Hash::make('password'),
        ]);

        $this->generateTesPenalaranUmum($user);
        $this->generateTesPengetahuanUmum($user);
    }

    private function generateTesPenalaranUmum($user)
    {
        $test = Test::firstOrCreate([
            'name' => 'Tes Penalaran Umum',
            'code' => 'PU123',
            'num_order' => 1,
        ]);

        $packet = Packet::create([
            'test_id' => $test->id,
            'part' => 'A',
            'name' => 'Bagian A',
            'description' => 'Soal penalaran umum logika dan analisis',
            'type' => 'PG',
            'amount' => 15,
            'status' => 'active',
        ]);

        $this->generateSoal($user, $test, $packet, 'cat1/a.jpeg');
    }

    private function generateTesPengetahuanUmum($user)
    {
        $test = Test::create([
            'name' => 'Tes Pengetahuan Umum',
            'code' => 'TPU456',
            'num_order' => 2,
        ]);

        $packet = Packet::create([
            'test_id' => $test->id,
            'part' => 'A',
            'name' => 'Pengetahuan Umum A',
            'description' => 'Soal tentang wawasan umum dan nasional',
            'type' => 'PG',
            'amount' => 10,
            'status' => 'active',
        ]);

        $this->generateSoal($user, $test, $packet, 'cat2/b.jpeg', 10);
    }

    private function generateSoal($user, $test, $packet, $imagepath, $jumlah = 15)
    {
        $labels = ['A', 'B', 'C', 'D'];
        $tempAnswers = [];

        for ($i = 1; $i <= $jumlah; $i++) {
            $question = Question::create([
                'packet_id' => $packet->id,
                'number' => $i,
                'description' => "Contoh soal ke-$i untuk tes {$test->name}",
                'is_example' => false,
                'image' => $imagepath,
            ]);

            $options = [
                ['text' => 'Pilihan A', 'is_correct' => false],
                ['text' => 'Pilihan B', 'is_correct' => false],
                ['text' => 'Pilihan C', 'is_correct' => false],
                ['text' => 'Pilihan D', 'is_correct' => false],
            ];

            $correctIndex = rand(0, 3);
            $options[$correctIndex]['is_correct'] = true;

            foreach ($options as $index => $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $opt['text'],
                    'value' => $labels[$index],
                    'is_correct' => $opt['is_correct'],
                ]);
            }

            $tempAnswers[(string)$i] = $labels[$correctIndex];
        }

        Result::create([
            'user_id' => $user->id,
            'company_id' => 1,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'result' => rand(60, 100),
        ]);

        TestTemporary::create([
            'id_user' => $user->id,
            'test_id' => $test->id,
            'packet_id' => $packet->id,
            'part' => $packet->part,
            'json' => json_encode($tempAnswers),
            'result_temp' => count($tempAnswers),
        ]);
    }
}
