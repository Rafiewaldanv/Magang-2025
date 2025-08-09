<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestHistory extends Model
{
    protected $fillable = [
        'user_id', 'test_id', 'score', 'duration', 'started_at', 'finished_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
