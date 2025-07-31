<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // BUKAN 'Seeders'
use App\Models\Question;
use App\Models\Option;
use App\Models\Test;
use App\Models\Packet;

class SoalContohSeeder extends Seeder
{
    public function run(): void
    {
        $test = Test::firstOrCreate([
            'name' => 'Tes Dummy',
            'code' => 'TES001',
            'description' => 'Tes dummy untuk keperluan seeder',
            'test_time' => 30, // tambahkan jika field ini wajib
            'num_order' => 1
        ]);
        
        $packet = Packet::firstOrCreate([
            'test_id' => $test->id,// tambahkan test_id
            'name' => 'Paket Dummy',
            'description' => 'Paket soal untuk dummy insertSoal.js',
            'part' => '1',
            'type' => 'PG', // Pilihan Ganda
            'amount' => 5, // jumlah soal
            'status' => 'active',   
        ]);
        

        $soalTexts = [
            'Apa ibu kota negara Indonesia?',
            '2 + 2 = ?',
            'Siapa presiden pertama Indonesia?',
            'Apa warna bendera Indonesia?',
            'Binatang apa yang disebut raja hutan?'
        ];

        $optionsList = [
            ['Jakarta', 'Bandung', 'Surabaya', 'Medan'],
            ['2', '3', '4', '5'],
            ['Soeharto', 'BJ Habibie', 'Ir. Soekarno', 'Gus Dur'],
            ['Merah Putih', 'Hijau Kuning', 'Biru Putih', 'Merah Kuning'],
            ['Harimau', 'Gajah', 'Singa', 'Buaya']
        ];

        $corrects = ['A', 'C', 'C', 'A', 'C'];

        for ($i = 0; $i < count($soalTexts); $i++) {
            $question = Question::create([
                'packet_id' => $packet->id,
                'number' => $i + 1,
                'description' => $soalTexts[$i],
                'is_example' => false,
            ]);

            foreach ($optionsList[$i] as $index => $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'label' => chr(65 + $index), // A, B, C, D
                    'value' => $opt,
                    'is_correct' => chr(65 + $index) === $corrects[$i]
                ]);
            }
        }
    }
}
