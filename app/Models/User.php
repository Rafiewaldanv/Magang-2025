<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function testTemporaries()
    {
        return $this->hasMany(TestTemporary::class, 'id_user');
    }
}
