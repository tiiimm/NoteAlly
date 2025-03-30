<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('upload.form');
});

Route::get('/upload', [PdfController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [PdfController::class, 'uploadAndSummarize'])->name('upload.summarize');
Route::post('/generate-flashcards', [PdfController::class, 'generateFlashcards'])->name('generate.flashcards');