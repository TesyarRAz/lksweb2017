<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function members()
    {
    	return $this->belongsToMany(User::class, 'board_members')->using(BoardMember::class);
    }

    public function lists()
    {
    	return $this->hasMany(BoardList::class);
    }
}
