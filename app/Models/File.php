<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['user_id', 'title', 'url'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function flashcards() {
        return $this->hasMany(Flashcard::class);
    }
}
