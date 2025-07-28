<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyTesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert ke table test
        $testId = DB::table('tests')->insertGetId([
            'name' => 'Tes Kepribadian TIKI',
            'code' => Str::uuid(), // code unik
            'num_order' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Insert ke table packet
        $packetId = DB::table('packets')->insertGetId([
            'test_id' => $testId,
            'part' => 1,
            'name' => 'Paket A',
            'description' => 'Tes Kepribadian Bagian A',
            'type' => 'pilihan_ganda',
            'amount' => 5,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Insert 5 soal ke table question
        for ($i = 1; $i <= 5; $i++) {
            DB::table('questions')->insert([
                'packet_id' => $packetId,
                'number' => $i,
                'description' => 'Ini adalah soal nomor ' . $i,
                'is_example' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 4. Insert dummy ke test_temporary
        DB::table('test_temporary')->insert([
            'id_user' => 1, // anggap user ID 1
            'test_id' => $testId,
            'packet_id' => $packetId,
            'part' => 1,
            'json' => json_encode([
                ['question_id' => 1, 'answer' => 'A'],
                ['question_id' => 2, 'answer' => 'C'],
            ]),
            'result_temp' => json_encode([
                'total_score' => 8,
                'category' => 'Ekstrovert'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. Insert dummy ke table result
        // 5. Insert dummy ke table result
// 5. Insert dummy ke table result
DB::table('results')->insert([
    'company_id' => 1,
    'test_id' => $testId,
    'user_id' => 1,
    'json' => json_encode([
        ['question_id' => 1, 'answer' => 'A'],
        ['question_id' => 2, 'answer' => 'C'],
    ]),
    'created_at' => now(),
    'updated_at' => now()
]);


    }
}
