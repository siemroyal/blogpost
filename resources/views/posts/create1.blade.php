@extends("layouts.app")
@section("title", "Drafting: New Post")

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f1f5f9; /* Slate 100 */
        color: #1e293b;
    }

    /* Minimalist Navbar-style Header */
    .top-action-bar {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .workspace-container {
        padding-top: 3rem;
        padding-bottom: 5rem;
    }

    /* Card Styling */
    .card-modular {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
    }

    .card-modular .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    /* Input Styling */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .input-canvas {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.8rem 1rem;
        transition: all 0.2s ease;
        background-color: #fff;
    }

    .input-canvas:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #fff;
    }

    .title-input {
        font-size: 1.75rem;
        font-weight: 800;
        border-color: transparent;
        padding-left: 0;
        padding-right: 0;
    }
    .title-input:focus {
        border-color: transparent;
        box-shadow: none;
    }

    /* Image Upload UI */
    .image-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        transition: 0.3s;
        background: #f8fafc;
    }
    .image-dropzone:hover {
        border-color: #6366f1;
        background: #eff6ff;
    }

    /* Status Pills */
    .btn-status {
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        width: 100%;
        text-align: left;
        margin-bottom: 0.5rem;
    }
    .btn-check:checked + .btn-status-draft { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }
    .btn-check:checked + .btn-status-pub { background: #eef2ff; color: #4338ca; border-color: #6366f1; }

</style>
@endpush

@section("content")
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Top Action Bar -->
    <div class="top-action-bar shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="mb-0 fw-bold">Editor <span class="text-muted fw-normal ms-2">/ New Story</span></h5>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-indigo px-4 py-2 rounded-pill fw-bold" style="background: #6366f1; color: white;">
                    Create Post
                </button>
            </div>
        </div>
    </div>

    <div class="container workspace-container">
        <div class="row">
            <!-- Main Writing Canvas -->
            <div class="col-lg-8">
                <div class="card-modular border-0 bg-transparent mb-4">
                    <input type="text" name="title" id="title"
                        class="form-control title-input @error('title') is-invalid @enderror"
                        placeholder="Untitled Post" value="{{ old('title') }}" required>

                    <div class="d-flex align-items-center text-muted small mt-1">
                        <i class="bi bi-link-45deg me-1"></i>
                        <span>slug:</span>
                        <input type="text" name="slug" id="slug" class="border-0 bg-transparent p-0 ms-1 text-primary fw-medium" value="{{ old('slug') }}" readonly style="outline: none;">
                    </div>
                    @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="card-modular">
                    <div class="card-header">Main Content</div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label">Post Excerpt</label>
                            <textarea name="excerpt" rows="2" class="form-control input-canvas" placeholder="A short introduction...">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Full Content</label>
                            <textarea name="body" id="body" rows="15" class="form-control input-canvas @error('body') is-invalid @enderror" placeholder="Begin your story here..." required>{{ old('body') }}</textarea>
                            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Panels -->
            <div class="col-lg-4">

                <!-- Publishing Module -->
                <div class="card-modular">
                    <div class="card-header">Publishing</div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select input-canvas @error('category_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Choose category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status</label>
                            <div class="status-options">
                                <input type="radio" class="btn-check" name="status" id="s_draft" value="draft" checked>
                                <label class="btn btn-status btn-status-draft" for="s_draft">
                                    <i class="bi bi-pencil-fill me-2"></i> Save as Draft
                                </label>

                                <input type="radio" class="btn-check" name="status" id="s_pub" value="published">
                                <label class="btn btn-status btn-status-pub" for="s_pub">
                                    <i class="bi bi-send-fill me-2"></i> Ready to Publish
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Publication Date</label>
                            <input type="datetime-local" name="published_at" class="form-control input-canvas shadow-none">
                        </div>
                    </div>
                </div>

                <!-- Featured Media Module -->
                <div class="card-modular">
                    <div class="card-header">Featured Image</div>
                    <div class="card-body p-4 text-center">
                        <div class="image-dropzone" id="img-trigger">
                            <i class="bi bi-cloud-arrow-up text-muted fs-2"></i>
                            <p class="small text-muted mb-0 mt-2">Recommended: 1200x630px</p>
                            <input type="file" name="post_image" id="post_image" class="d-none" accept="image/*">
                        </div>
                        <div id="preview-container" class="mt-3 d-none">
                            <img id="img-preview" src="" class="img-fluid rounded shadow-sm border">
                            <button type="button" class="btn btn-link btn-sm text-danger mt-2 fw-bold text-decoration-none" id="img-remove">Remove Image</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
@endsection

@push("scripts")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const title = document.getElementById('title');
        const slug = document.getElementById('slug');
        const fileInput = document.getElementById('post_image');
        const trigger = document.getElementById('img-trigger');
        const previewCont = document.getElementById('preview-container');
        const previewImg = document.getElementById('img-preview');
        const removeBtn = document.getElementById('img-remove');

        // Logic for Auto-Slug
        title.addEventListener('input', function() {
            slug.value = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        });

        // Logic for Image Preview
        trigger.onclick = () => fileInput.click();

        fileInput.onchange = function() {
            const [file] = this.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewCont.classList.remove('d-none');
                trigger.classList.add('d-none');
            }
        };

        removeBtn.onclick = function() {
            fileInput.value = '';
            previewCont.classList.add('d-none');
            trigger.classList.remove('d-none');
        };
    });
</script>
@endpush
