<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Default Company',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
