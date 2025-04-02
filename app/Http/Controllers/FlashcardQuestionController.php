<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardQuestionController extends Controller
{
    public function index($flashcardId) {
        return Flashcard::with('questions')->findOrFail($flashcardId);
    }
}
