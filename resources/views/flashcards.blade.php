@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="pdf-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-cards text-primary"></i> Flashcards: {{ $filename }}</h2>
                <a href="{{ route('upload.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Upload
                </a>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-lines me-2"></i>Original Text Excerpt</h5>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            <pre style="white-space: pre-wrap;">{{ Str::limit($originalText, 2000) }}</pre>
                        </div>
                        <div class="card-footer text-muted">
                            Showing first 2000 characters
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-cards me-2"></i>Generated Flashcards</h5>
                                <button onclick="printFlashcards()" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            @foreach($flashcards as $index => $flashcard)
                                <div class="flashcard mb-3 p-3 border rounded">
                                    <h5>Q{{ $index + 1 }}: {{ $flashcard['question'] }}</h5>
                                    <div class="answer" style="display: none;">
                                        <p class="mb-0"><strong>Answer:</strong> {{ $flashcard['answer'] }}</p>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary mt-2 toggle-answer">
                                        Show Answer
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle answer visibility
    document.querySelectorAll('.toggle-answer').forEach(button => {
        button.addEventListener('click', function() {
            const answerDiv = this.previousElementSibling;
            if (answerDiv.style.display === 'none') {
                answerDiv.style.display = 'block';
                this.textContent = 'Hide Answer';
            } else {
                answerDiv.style.display = 'none';
                this.textContent = 'Show Answer';
            }
        });
    });

    function printFlashcards() {
        const printContent = document.querySelector('.col-md-6 .card-body').innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }
</script>
@endsection