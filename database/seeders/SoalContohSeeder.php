<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Packet;
use App\Models\Question;
use App\Models\Option;

class SoalContohSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Test
        $test = Test::firstOrCreate(
            ['id' => 1],
            ['name' => 'Tes Contoh', 'code' => 'tes-contoh']
        );

        // Buat Packet
        $packet = Packet::firstOrCreate(
            ['id' => 1],
            [
                'test_id' => $test->id,
                'name' => 'Paket A',
                'part' => 1,
                'type' => 'pilihan_ganda',
                'amount' => 10 // atau jumlah soal sebenarnya
            ]
        );
        
        

        // Buat Soal
        $question = Question::firstOrCreate(
            ['packet_id' => $packet->id, 'number' => 1],
            [
                'description' => 'Apa ibukota Indonesia?',
                'type' => 'radio', // radio = pilihan ganda
            ]
        );

        // Buat Opsi
        $options = [
            ['code' => 'A', 'text' => 'Jakarta'],
            ['code' => 'B', 'text' => 'Bandung'],
            ['code' => 'C', 'text' => 'Surabaya'],
            ['code' => 'D', 'text' => 'Medan'],
        ];

        foreach ($options as $opt) {
            Option::firstOrCreate([
                'question_id' => $question->id,
                'code' => $opt['code'],
            ], [
                'text' => $opt['text'],
            ]);
        }

        $this->command->info('✅ Data test, packet, soal, dan opsi berhasil ditambahkan!');
    }
}
