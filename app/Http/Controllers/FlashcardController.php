<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index() {
        return Flashcard::with('questions')->get();
    }

    //for when user regenerates another set of questions for the same pdf
    public function store(Request $request) {
        $validated = $request->validate([
            'file_id' => 'required|exists:files,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.answer_text' => 'required|string',
        ]);

        $flashcard = Flashcard::create([
            'file_id' => $validated['file_id'],
        ]);

        $flashcard->questions()->createMany($validated['questions']);

        return response()->json($flashcard->load('questions'), 201);
    }

    public function show($id) {
        $flashcard = Flashcard::with('questions')->findOrFail($id);
        return response()->json($flashcard);
    }
}
