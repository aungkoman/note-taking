<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}

