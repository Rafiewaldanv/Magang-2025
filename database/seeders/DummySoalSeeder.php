<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Packet;
use App\Models\Question;

class DummySoalSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat test
       $test = Test::firstOrCreate(
    ['id' => 1], // force pakai id 1 agar sinkron
    [
        'code' => '1',
        'name' => 'Tes Logika Dasar'
    ]
);

        // 2. Buat packet untuk test
        $packet = Packet::create([
            'test_id' => $test->id,
            'name' => 'Paket A',
            'part' => 'Bagian 1'
        ]);

        // 3. Buat beberapa soal untuk packet
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'packet_id' => $packet->id,
                'number' => $i, // Tambahkan ini
                'text' => "Soal nomor $i: Berapakah hasil dari $i + $i?",
                'description' => 'Soal Matematika Dasar',
                'option_a' => $i + 1,
                'option_b' => $i + 2,
                'option_c' => $i + $i,
                'option_d' => $i * 2,
                'correct_answer' => 'C'
            ]);
        }
        
    }
}
