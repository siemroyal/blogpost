@extends("layouts.app")
@section("title", "Edit Post: " . $post->title)

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root { --primary-hex: #4361ee; --bg-light: #f8fafc; }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }

    .edit-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }

    .form-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .sidebar-card {
        background: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-label { font-weight: 600; color: #475569; font-size: 0.9rem; }

    .current-image-preview {
        width: 100%;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }

    .status-badge-radio input:checked + label {
        background-color: var(--primary-hex);
        color: white;
        border-color: var(--primary-hex);
    }
</style>
@endpush

@section("content")
<div class="edit-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Posts</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-0">Edit: {{ Str::limit($post->title, 40) }}</h2>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-eye me-1"></i> Preview
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column: Content -->
            <div class="col-lg-8">
                <div class="form-card">
                    {{-- Title --}}
                    <div class="mb-4">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                            value="{{ old('title', $post->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="mb-4">
                        <label for="slug" class="form-label text-muted small">Permalink</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">/posts/</span>
                            <input type="text" name="slug" id="slug" class="form-control bg-light border-start-0"
                                value="{{ old('slug', $post->slug) }}" readonly>
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="mb-4">
                        <label for="excerpt" class="form-label">Excerpt (Summary)</label>
                        <textarea name="excerpt" id="excerpt" rows="3" class="form-control" placeholder="A short intro...">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    {{-- Body --}}
                    <div class="mb-0">
                        <label for="body" class="form-label">Content</label>
                        <textarea name="body" id="body" rows="15" class="form-control @error('body') is-invalid @enderror" required>{{ old('body', $post->body) }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="col-lg-4">
                {{-- Publish Settings --}}
                <div class="sidebar-card shadow-sm">
                    <h5 class="fw-bold mb-4">Publish Details</h5>

                    <div class="mb-4">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Status</label>
                        <div class="btn-group w-100 status-badge-radio" role="group">
                            <input type="radio" class="btn-check" name="status" id="draft" value="draft" {{ old('status', $post->status) == 'draft' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="draft">Draft</label>

                            <input type="radio" class="btn-check" name="status" id="published" value="published" {{ old('status', $post->status) == 'published' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="published">Publish</label>

                            <input type="radio" class="btn-check" name="status" id="archived" value="archived" {{ old('status', $post->status) == 'archived' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="archived">Archive</label>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="published_at" class="form-label">Publish Date</label>
                        <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                            value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="sidebar-card shadow-sm">
                    <h5 class="fw-bold mb-3">Featured Image</h5>

                    <!-- Display Existing Image -->
                    <div id="image-preview-wrapper">
                        @if($post->post_image)
                            <img src="{{ asset('storage/' . $post->post_image) }}" class="current-image-preview" id="preview-img">
                        @else
                            <img src="https://placehold.co/600x400?text=No+Image" class="current-image-preview" id="preview-img">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="post_image" class="form-label">Change Image</label>
                        <input type="file" name="post_image" id="post_image" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text small text-muted">Leave empty to keep current image.</div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                        <i class="bi bi-cloud-upload me-2"></i> Update Post
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-link text-muted mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push("scripts")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');
        const imageInput = document.getElementById('post_image');
        const previewImg = document.getElementById('preview-img');

        // Auto-slug generation
        title.addEventListener('input', function() {
            slug.value = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        });

        // New Image Preview logic
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
