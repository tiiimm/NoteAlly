<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['flashcard_id', 'question', 'answer'];

    public function flashcard() {
        return $this->belongsTo(Flashcard::class);
    }
}
