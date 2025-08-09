<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'num_order'];

    public function packets()
    {
        return $this->hasMany(Packet::class);
    }
    public function histories()
    {
        return $this->hasMany(TestHistory::class);
    }
    
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}