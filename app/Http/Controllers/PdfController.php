<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class PdfController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadAndSummarize(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240', // 10MB max
        ]);

        try {
            // Store the uploaded file temporarily
            $file = $request->file('pdf');
            
            // Extract text from PDF
            $pdfText = $this->extractTextFromPdf($file);
            
            // Summarize the text using OpenAI
            $summary = $this->summarizeWithOpenAI($pdfText);

            return view('summary', [
                'originalText' => $pdfText,
                'summary' => $summary,
                'filename' => $request->file('pdf')->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing PDF: ' . $e->getMessage());
        }
    }

    private function extractTextFromPdf($path)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        $text = $pdf->getText();
        
        // Clean up the text
        $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces/newlines
        return trim($text);
    }

    private function summarizeWithOpenAI($text)
    {
        // Limit the text to 12000 characters to avoid hitting token limits
        $text = substr($text, 0, 12000);

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant that summarizes PDF content into clear bullet points. ' .
                                 'Focus on the key information and main ideas. ' .
                                 'Use concise language and organize the information logically.'
                ],
                [
                    'role' => 'user',
                    'content' => "Please summarize the following text into bullet points:\n\n" . $text
                ]
            ],
            'temperature' => 0.3,
        ]);

        return $response->choices[0]->message->content;
    }

    public function generateFlashcards(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240',
            'flashcard_count' => 'required|integer|min:3|max:20',
            'difficulty' => 'required|in:basic,intermediate,advanced'
        ]);

        try {
            $file = $request->file('pdf');
            $pdfText = $this->extractTextFromPdf($file);
            
            $flashcards = $this->generateFlashcardsWithOpenAI(
                $pdfText,
                $request->input('flashcard_count'),
                $request->input('difficulty')
            );

            return view('flashcards', [
                'originalText' => $pdfText,
                'flashcards' => $flashcards,
                'filename' => $file->getClientOriginalName(),
                'count' => $request->input('flashcard_count'),
                'difficulty' => $request->input('difficulty')
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating flashcards: ' . $e->getMessage());
        }
    }

    private function generateFlashcardsWithOpenAI($text, $count, $difficulty)
    {
        $text = substr($text, 0, 12000); // Limit text size

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a helpful assistant that generates EXACTLY $count flashcards from content at $difficulty level. " .
                                "Create clear question-answer pairs. Format each EXACTLY as: 'Q: [question]\nA: [answer]' " .
                                "with exactly ONE blank line between pairs. DO NOT include any additional commentary or notes. " .
                                "You MUST generate exactly $count flashcards - no more, no less."
                ],
                [
                    'role' => 'user',
                    'content' => "Please generate EXACTLY $count $difficulty level flashcards from this text:\n\n" . $text .
                                "\n\nRemember: Only output $count flashcards, formatted exactly as specified."
                ]
            ],
            'temperature' => $difficulty === 'basic' ? 0.2 : ($difficulty === 'advanced' ? 0.4 : 0.3),
        ]);

        return $this->parseFlashcards($response->choices[0]->message->content);
    }

    private function parseFlashcards($flashcardsText)
    {
        $flashcards = [];
        $pairs = preg_split('/\n\n+/', $flashcardsText);
        
        foreach ($pairs as $pair) {
            if (preg_match('/Q:\s*(.+?)\nA:\s*(.+)/s', $pair, $matches)) {
                $flashcards[] = [
                    'question' => trim($matches[1]),
                    'answer' => trim($matches[2])
                ];
            }
        }
        
        return $flashcards;
    }
}