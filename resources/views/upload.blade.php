@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="pdf-container">
            <h2 class="mb-4"><i class="fas fa-file-pdf text-danger"></i> PDF Summarizer</h2>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    Upload PDF File
                </div>
                <div class="card-body">
                    <form action="{{ route('upload.summarize') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="mb-3">
                            <label for="pdfFile" class="form-label">Select PDF (Max 10MB)</label>
                            <input class="form-control" type="file" id="pdfFile" name="pdf" accept=".pdf" required>
                            @error('pdf')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2 mb-3">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-align-left me-1"></i> Generate Summary
                            </button>
                            
                            <button type="button" class="btn btn-success flex-grow-1" data-bs-toggle="modal" data-bs-target="#flashcardsModal">
                                <i class="fas fa-cards me-1"></i> Generate Flashcards
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Flashcards Modal -->
            <div class="modal fade" id="flashcardsModal" tabindex="-1" aria-labelledby="flashcardsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="flashcardsModalLabel">Flashcard Options</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('generate.flashcards') }}" method="POST" enctype="multipart/form-data" id="flashcardsForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="flashcardCount" class="form-label">Number of Flashcards</label>
                                    <input type="number" class="form-control" id="flashcardCount" name="flashcard_count" min="3" max="20" value="10">
                                    <small class="text-muted">Choose between 3-20 flashcards</small>
                                </div>
                                <div class="mb-3">
                                    <label for="difficulty" class="form-label">Difficulty Level</label>
                                    <select class="form-select" id="difficulty" name="difficulty">
                                        <option value="basic">Basic</option>
                                        <option value="intermediate" selected>Intermediate</option>
                                        <option value="advanced">Advanced</option>
                                    </select>
                                </div>
                                <!-- No hidden file input here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Generate Flashcards</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4 summary-box">
                <h5><i class="fas fa-lightbulb text-warning me-2"></i>How it works:</h5>
                <ol>
                    <li>Upload any PDF document (reports, articles, etc.)</li>
                    <li>Choose between summary or flashcard generation</li>
                    <li>For flashcards, select quantity and difficulty</li>
                    <li>Get your AI-processed content in seconds</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadForm = document.getElementById('uploadForm');
        const flashcardsForm = document.getElementById('flashcardsForm');
        const pdfFileInput = document.getElementById('pdfFile');
        
        // Store the file when selected
        let selectedFile = null;
        
        pdfFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                selectedFile = this.files[0];
            }
        });
        
        // Handle flashcards form submission
        flashcardsForm.addEventListener('submit', function(e) {
            if (!selectedFile) {
                e.preventDefault();
                alert('Please select a PDF file first');

                const modal = bootstrap.Modal.getInstance(document.getElementById('flashcardsModal'));
                modal.hide();
                return;
            }
            
            // Create a new FormData object
            const formData = new FormData(uploadForm);
            formData.append('pdf', selectedFile);
            formData.append('flashcard_count', document.getElementById('flashcardCount').value);
            formData.append('difficulty', document.getElementById('difficulty').value);
            
            // Submit via fetch API
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                console.log(response);
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                document.open();
                document.write(html);
                document.close();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating flashcards: ' + error.message);
                submitBtn.innerHTML = 'Generate Flashcards';
                submitBtn.disabled = false;
            });


            const modal = bootstrap.Modal.getInstance(document.getElementById('flashcardsModal'));
            modal.hide();
        });
    });
</script>
@endsection

<style>
    .pdf-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    .summary-box {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>