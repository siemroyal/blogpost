@extends("layouts.app")
@section("title", "Edit Post")

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }

    /* Subtle Inputs */
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
        background-color: #fff;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Card Styling */
    .glass-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .section-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 1rem;
        display: block;
    }

    /* Image Preview Container */
    .image-dropzone {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: 0.3s;
    }
    .image-dropzone:hover { border-color: #6366f1; }
    .preview-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: rgba(0,0,0,0.5);
        color: white;
        padding: 5px;
        font-size: 0.7rem;
        cursor: pointer;
    }

    /* Sidebar Sticky */
    .sidebar-sticky { position: sticky; top: 2rem; }
</style>
@endpush

@section("content")
<div class="container py-5">
    @include("partials.message")
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Header -->
        <div class="row align-items-center mb-5">
            <div class="col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Article</li>
                    </ol>
                </nav>
                <h1 class="fw-bold h2 mb-0">Modify your story</h1>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-cloud-check me-1"></i> Update Post
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <span class="section-label">Core Content</span>

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Article Headline</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg fw-bold @error('title') is-invalid @enderror"
                            placeholder="A compelling title..." value="{{ old('title', $post->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">/</span>
                            <input type="text" name="slug" id="slug" class="form-control bg-light" value="{{ old('slug', $post->slug) }}" readonly>
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Short Summary (Excerpt)</label>
                        <textarea name="excerpt" rows="3" class="form-control" placeholder="Summarize your story...">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    {{-- Body --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Main Body Content</label>
                        <textarea name="body" id="body" rows="18" class="form-control @error('body') is-invalid @enderror" placeholder="Once upon a time..." required>{{ old('body', $post->body) }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-sticky">

                    {{-- Status Card --}}
                    <div class="glass-card mb-4">
                        <span class="section-label">Organization</span>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Category</label>
                            <select name="category_id" class="form-select shadow-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Publication Status</label>
                            <select name="status" class="form-select shadow-none">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold small">Publish Date</label>
                            <input type="datetime-local" name="published_at" class="form-control shadow-none"
                                value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>

                    {{-- Featured Image Card --}}
                    <div class="glass-card">
                        <span class="section-label">Featured Image</span>
                        <div class="image-dropzone">
                            <img id="image-preview" src="{{ $post->post_image ? asset('storage/'.$post->post_image) : 'https://placehold.co/400x300?text=No+Featured+Image' }}"
                                class="img-fluid rounded-3 mb-2 shadow-sm">

                            <label for="post_image" class="btn btn-sm btn-light border w-100 rounded-pill mt-2 fw-semibold">
                                <i class="bi bi-camera me-1"></i> Change Image
                            </label>
                            <input type="file" name="post_image" id="post_image" class="d-none" accept="image/*">
                        </div>
                        <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i> Recommended size: 1200x800 for best resolution.</p>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push("scripts")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const imageInput = document.getElementById('post_image');
        const imagePreview = document.getElementById('image-preview');

        // Automatic Slug Logic
        titleInput.addEventListener('input', function() {
            let text = this.value.toLowerCase()
                .trim()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slugInput.value = text;
        });

        // Instant Image Preview Logic
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
