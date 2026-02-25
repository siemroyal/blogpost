@extends("layouts.app")
@section("title", "Write Post")

@section("content")
<div class="bg-white min-vh-100">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Sticky Top Action Bar -->
        <div class="sticky-top bg-white border-bottom py-2 shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('posts.index') }}" class="btn btn-link text-decoration-none text-muted p-0 me-3">
                        <i class="bi bi-x-lg fs-5"></i>
                    </a>
                    <span class="text-uppercase fw-bold ls-1 small text-muted">Draft in Content</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <select name="status" class="form-select form-select-sm border-0 bg-light fw-semibold" style="width: auto;">
                        <option value="draft">Save as Draft</option>
                        <option value="published">Publish Now</option>
                        <option value="archived">Archive</option>
                    </select>
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold">Publish</button>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <!-- Image Upload Dropzone Style -->
                    <div class="mb-5">
                        <div class="post-image-upload position-relative border-0 rounded-4 overflow-hidden bg-light d-flex align-items-center justify-center shadow-sm" style="min-height: 300px;">
                            <img id="image-display" src="" class="position-absolute w-100 h-100 object-fit-cover d-none">
                            <div id="upload-placeholder" class="text-center w-100">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted small fw-medium mt-2">Add a featured image</p>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="document.getElementById('post_image').click()">Browse Files</button>
                            </div>
                            <input type="file" name="post_image" id="post_image" class="d-none" accept="image/*" onchange="handleImagePreview(this)">
                        </div>
                        @error('post_image') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <!-- Title with Floating Effect -->
                    <div class="mb-2">
                        <textarea name="title" id="title" class="form-control form-control-lg border-0 fs-1 fw-bold p-0 shadow-none bg-transparent"
                            placeholder="Post Title" rows="1" style="resize: none;" required></textarea>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Slug Preview -->
                    <div class="mb-4 d-flex align-items-center text-muted small">
                        <i class="bi bi-link-45deg me-1"></i>
                        <span>yourdomain.com/</span>
                        <input type="text" name="slug" id="slug" class="border-0 p-0 m-0 text-muted bg-transparent shadow-none w-auto" readonly>
                    </div>

                    <!-- Category and Date Selector (Inline) -->
                    <div class="d-flex flex-wrap gap-4 mb-5 pb-4 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-tag me-2 text-primary"></i>
                            <select name="category_id" class="form-select form-select-sm border-0 shadow-none fw-medium" required>
                                <option value="">Choose Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                            <input type="datetime-local" name="published_at" class="form-control form-control-sm border-0 shadow-none text-muted fw-medium">
                        </div>
                    </div>

                    <!-- Excerpt (Sub-header) -->
                    <div class="mb-4">
                        <textarea name="excerpt" class="form-control border-0 p-0 shadow-none fs-5 italic text-muted"
                            placeholder="Write a short subtitle or excerpt..." rows="2" style="resize: none;"></textarea>
                    </div>

                    <!-- Main Body (Editor) -->
                    <div class="content-editor mb-5">
                        <input id="body" type="hidden" name="body">
                        <trix-editor input="body" placeholder="Tell your story..." class="border-0 shadow-none p-0 fs-5"></trix-editor>
                        @error('body') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* Clean Typography */
    @import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1a1a1a;
    }

    /* Large Title Textarea Auto-resize behavior */
    #title {
        line-height: 1.2;
        overflow: hidden;
    }

    /* Trix Editor Style overrides for "Medium" look */
    trix-editor {
        font-family: 'Lora', serif;
        font-size: 1.25rem;
        line-height: 1.8;
        color: #292929;
        min-height: 500px !important;
    }

    trix-toolbar {
        position: sticky;
        top: 60px; /* Under the action bar */
        background: white;
        z-index: 10;
        padding: 10px 0 !important;
        border-bottom: 1px solid #eee !important;
    }

    .trix-button-row { border: none !important; }

    .object-fit-cover { object-fit: cover; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
    // Auto-expand title textarea
    const titleArea = document.getElementById('title');
    titleArea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';

        // Update Slug
        let slug = this.value.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Image Preview logic
    function handleImagePreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-display').src = e.target.result;
                document.getElementById('image-display').classList.remove('d-none');
                document.getElementById('upload-placeholder').classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
