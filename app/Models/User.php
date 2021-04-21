<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function tokens()
    {
    	return $this->hasMany(LoginToken::class);
    }

    public function boards()
    {
    	return $this->belongsToMany(Board::class, 'board_members')->using(BoardMember::class);
    }
}
