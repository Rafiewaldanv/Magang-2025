<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['packet_id', 'number', 'description', 'is_example'];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
