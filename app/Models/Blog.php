<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];
    protected $hidden = ['media'];
    protected $appends = ['photo', 'reacted'];

    public function getPhotoAttribute(){
        return $this->getFirstMediaUrl('photo');
    }

    public function getReactedAttribute(){
        return $this->reactions()->where('user_id', auth()->id())->first() != null;
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function reactions(){
        return $this->hasMany(Reaction::class);
    }
}
