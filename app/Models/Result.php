<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'company_id', 'test_id', 'json', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }
}
