<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestTemporary extends Model
{
    protected $table = 'test_temporary';

    protected $fillable = [
        'user_id',
        'test_id',
        'packet_id',
        'part',
        'json',
        'result_temp',
    ];

    // Relasi opsional ke user/test/packet kalau kamu butuh
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function test() {
        return $this->belongsTo(Test::class);
    }

    public function packet() {
        return $this->belongsTo(Packet::class);
    }
}
