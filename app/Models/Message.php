<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['attachment'];
    protected $hidden = ['media'];

    public function getAttachmentAttribute(){
        return $this->getFirstMediaUrl('attachment');
    }

    public function senderable(){
        return $this->morphTo();
    }

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }
}
