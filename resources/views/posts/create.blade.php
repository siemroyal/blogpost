@extends("layouts.app")
@section("title", "Create New Post")

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #4361ee;
        --bg-body: #f8fafc;
    }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); color: #334155; }

    .header-banner {
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
        padding: 4rem 0;
        color: white;
        border-radius: 0 0 2rem 2rem;
        margin-bottom: -4rem;
    }

    .main-card {
        background: white;
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        padding: 2.5rem;
    }

    .form-label { font-weight: 600; font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem; }

    .form-control, .form-select {
        border-radius: 0.6rem;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    /* Modern Radio Group */
    .status-group .btn-check:checked + .btn {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .status-group .btn {
        border-radius: 0.6rem;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    /* Image Upload Box */
    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        background: #fcfcfd;
        cursor: pointer;
        transition: 0.3s;
    }
    .upload-box:hover { border-color: var(--primary); background: #f0f4ff; }
    .preview-img { width: 100%; border-radius: 0.75rem; margin-top: 1rem; display: none; }
</style>
@endpush

@section("content")
<div class="container-fluid p-0">
    <!-- Top Decorative Header -->
    <header class="header-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-2">Create a New Post</h1>
                    <p class="lead opacity-75">Fill in the details below to share your story.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('posts.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-collection me-2"></i> View Posts
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Form Section -->
    <div class="container" style="position: relative; z-index: 10;">
        <div class="main-card">
            @include("partials.message")
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-5">
                    <!-- Left: Content Input -->
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <label for="title" class="form-label">Post Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                placeholder="Enter a unique title..." value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="form-label text-muted">URL Slug (Auto-generated)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted small">/posts/</span>
                                <input type="text" name="slug" id="slug" class="form-control bg-light border-start-0" value="{{ old('slug') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="excerpt" class="form-label">Excerpt (Optional)</label>
                            <textarea name="excerpt" id="excerpt" rows="2" class="form-control" placeholder="Brief summary of your post...">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label for="body" class="form-label">Content Body <span class="text-danger">*</span></label>
                            <textarea name="body" id="body" rows="12" class="form-control @error('body') is-invalid @enderror" placeholder="Write your content here..." required>{{ old('body') }}</textarea>
                            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Right: Metadata -->
                    <div class="col-lg-4">
                        <div class="mb-5 p-4 bg-light rounded-4">
                            <h5 class="fw-bold mb-4">Publishing Info</h5>

                            <div class="mb-4">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status</label>
                                <div class="d-flex gap-2 status-group">
                                    <input type="radio" class="btn-check" name="status" id="draft" value="draft" checked>
                                    <label class="btn btn-outline-light w-100 shadow-sm" for="draft">Draft</label>

                                    <input type="radio" class="btn-check" name="status" id="publish" value="published">
                                    <label class="btn btn-outline-light w-100 shadow-sm" for="publish">Publish</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="published_at" class="form-label">Publish Date (Optional)</label>
                                <input type="datetime-local" name="published_at" id="published_at" class="form-control">
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="fw-bold mb-3">Featured Image</h5>
                            <div class="upload-box" id="upload-trigger">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                                <p class="mb-0 small fw-medium mt-2">Click to select image</p>
                                <input type="file" name="post_image" id="post_image" class="d-none" accept="image/*">
                                <img id="preview" class="preview-img shadow-sm" src="">
                                <button type="button" id="remove-btn" class="btn btn-sm btn-danger mt-3 d-none">Remove</button>
                            </div>
                            @error('post_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid mt-auto">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
                                <i class="bi bi-save me-2"></i> Save Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');
        const imgInput = document.getElementById('post_image');
        const trigger = document.getElementById('upload-trigger');
        const preview = document.getElementById('preview');
        const removeBtn = document.getElementById('remove-btn');

        // Auto-slug Logic
        title.addEventListener('keyup', function() {
            let text = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slug.value = text;
        });

        // Image Preview Logic
        trigger.addEventListener('click', () => imgInput.click());

        imgInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    removeBtn.classList.remove('d-none');
                    trigger.querySelector('i').style.display = 'none';
                    trigger.querySelector('p').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            imgInput.value = '';
            preview.style.display = 'none';
            removeBtn.classList.add('d-none');
            trigger.querySelector('i').style.display = 'inline-block';
            trigger.querySelector('p').style.display = 'block';
        });
    });
</script>
@endpush
