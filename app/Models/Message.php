<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function senderable(){
        return $this->morphTo();
    }

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }
}
