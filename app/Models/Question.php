<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
    'test_id',
    'packet_id',
    'number',         // ✅ tambahkan
    'description',    // ✅ tambahkan
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'option_e',
    'correct_option',
    'is_example',
];
    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
