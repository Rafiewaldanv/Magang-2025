<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'packet_id', 'text', 'option_a', 'option_b',
        'option_c', 'option_d', 'correct_answer'
    ];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }
}
