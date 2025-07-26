<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Packet;

class DummyTestSeeder extends Seeder
{
    public function run()
    {
        $test = Test::create([
            'name' => 'Tes Contoh',
            'code' => 'contoh', // ini akan dipakai jadi 'path'
        ]);
        Packet::create([
            'test_id' => $test->id,
            'part' => 1,
            'name' => 'Paket A', // <-- tambahkan ini
        ]);
        
    }
}
