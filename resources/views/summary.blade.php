@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="pdf-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt text-primary"></i> Summary: {{ $filename }}</h2>
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
                                <h5 class="mb-0"><i class="fas fa-sparkles me-2"></i>AI Summary</h5>
                                <button onclick="copySummary()" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-copy me-1"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            <pre style="white-space: pre-wrap;">{{ $summary }}</pre>
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
    function copySummary() {
        const summary = document.querySelector('.col-md-6 pre').innerText;
        navigator.clipboard.writeText(summary)
            .then(() => {
                const originalText = event.target.innerHTML;
                event.target.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                setTimeout(() => {
                    event.target.innerHTML = originalText;
                }, 2000);
            })
            .catch(err => {
                alert('Failed to copy: ' + err);
            });
    }
</script>
@endsection