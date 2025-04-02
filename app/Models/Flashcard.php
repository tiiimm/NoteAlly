<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $fillable = ['file_id'];

    public function file() {
        return $this->belongsTo(File::class);
    }

    public function questions() {
        return $this->hasMany(FlashcardQuestion::class);
    }
}
