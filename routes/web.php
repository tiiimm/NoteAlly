<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\FlashcardQuestionController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('upload.form');
});

Route::get('/upload', [PdfController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [PdfController::class, 'uploadAndSummarize'])->name('upload.summarize');
Route::post('/generate-flashcards', [PdfController::class, 'generateFlashcards'])->name('generate.flashcards');

Route::resource('files', FileController::class);
Route::resource('flashcards', FlashcardController::class);
Route::get('/flashcards/{flashcardId}/questions', [FlashcardQuestionController::class, 'index']);