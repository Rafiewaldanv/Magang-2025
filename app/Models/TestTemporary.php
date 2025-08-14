<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestTemporary extends Model
{
    use HasFactory;

    protected $table = 'test_temporary';

    protected $fillable = ['user_id', 'test_id', 'packet_id', 'part', 'json', 'result_temp'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}