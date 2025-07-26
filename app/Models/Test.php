<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'description',
        'is_active',
    ];

    // Relasi ke Packet (1 test bisa punya banyak part/paket soal)
    public function packets()
    {
        return $this->hasMany(Packet::class);
    }

    // Relasi ke Result (hasil akhir dari user)
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // Relasi ke TestTemporary (jawaban sementara per user)
    public function temporaries()
    {
        return $this->hasMany(TestTemporary::class);
    }
}
