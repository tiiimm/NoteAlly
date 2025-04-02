<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function index() {
        return File::with('flashcards.questions')->get();
    }

    //for first uploading of PDF, it will save pdf and save the generated questions
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.answer_text' => 'required|string',
        ]);

        $file = File::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'user_id' => Auth::id()
        ]);

        $flashcard = $file->flashcards()->create([]);

        $flashcard->questions()->createMany($validated['questions']);

        return response()->json($file->load('flashcards.questions'), 201);
    }
}
