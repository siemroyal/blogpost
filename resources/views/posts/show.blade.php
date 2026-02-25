@extends("layouts.app")
@section("title", $post->title)

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #fff; color: #1e293b; }

    /* Article Header */
    .article-header { padding-top: 3rem; padding-bottom: 3rem; }
    .category-badge {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6366f1;
    }
    .post-title { font-weight: 800; font-size: 3rem; line-height: 1.1; margin-top: 1rem; }
    .post-excerpt { font-size: 1.25rem; color: #64748b; line-height: 1.6; margin-top: 1.5rem; }

    /* Featured Image */
    .featured-image-wrapper {
        border-radius: 1.5rem;
        overflow: hidden;
        margin-bottom: 3rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }
    .featured-image { width: 100%; height: auto; max-height: 600px; object-fit: cover; }

    /* Content Area */
    .post-body {
        font-family: 'Lora', serif; /* Serif font for better reading experience */
        font-size: 1.2rem;
        line-height: 1.9;
        color: #334155;
    }
    .post-body p { margin-bottom: 2rem; }

    /* Sticky Sidebar Actions */
    .action-panel {
        position: sticky;
        top: 2rem;
        padding: 1.5rem;
        border-radius: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    /* Status Badges */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
    }
</style>
@endpush

@section("content")
<div class="container py-5">
    <!-- Top Navigation & Actions -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <a href="{{ route('posts.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Feed
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-pencil me-2"></i> Edit Post
            </a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Main Article Column -->
        <div class="col-lg-8">
            <header class="article-header text-center">
                <span class="category-badge">{{ $post->category->name }}</span>
                <h1 class="post-title">{{ $post->title }}</h1>

                <div class="d-flex align-items-center justify-content-center mt-4 text-muted">
                    <img src="{{ $post->post_image && file_exists(public_path('storage/' . $post->post_image))
                ? asset('storage/' . $post->post_image)
                : asset('images/no-image.jpg') }}"
                         class="rounded-circle me-2" width="32" alt="author">
                    <span class="fw-medium me-3">{{ $post->user->name }}</span>
                    <span class="me-3">&bull;</span>
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not Published' }}</span>
                </div>

                @if($post->excerpt)
                    <p class="post-excerpt">{{ $post->excerpt }}</p>
                @endif
            </header>

            <div class="featured-image-wrapper">
                <img src="{{ $post->post_image && file_exists(public_path('storage/' . $post->post_image))
                ? asset('storage/' . $post->post_image)
                : asset('images/no-image.jpg') }}"
                class="featured-image" alt="{{ $post->title }}">
            </div>


            <article class="post-body">
                {!! nl2br(e($post->body)) !!}
            </article>

            <hr class="my-5">

            <!-- Footer Meta -->
            <div class="d-flex justify-content-between align-items-center bg-light p-4 rounded-4 mb-5">
                <div>
                    <h6 class="text-uppercase small fw-bold text-muted mb-1">Tags / Category</h6>
                    <span class="badge bg-white text-primary border px-3 py-2 rounded-pill">{{ $post->category->name }}</span>
                </div>
                <div class="text-end">
                    <h6 class="text-uppercase small fw-bold text-muted mb-1">Share</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-white border rounded-circle"><i class="bi bi-twitter"></i></button>
                        <button class="btn btn-sm btn-white border rounded-circle"><i class="bi bi-facebook"></i></button>
                        <button class="btn btn-sm btn-white border rounded-circle"><i class="bi bi-link-45deg"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Info -->
        <div class="col-lg-3 offset-lg-1">
            <div class="action-panel">
                <h6 class="fw-bold mb-3 text-uppercase small">Post Status</h6>

                @if($post->status == 'published')
                    <div class="badge-status bg-success-subtle text-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i> Published
                    </div>
                @elseif($post->status == 'draft')
                    <div class="badge-status bg-warning-subtle text-warning d-flex align-items-center">
                        <i class="bi bi-pencil-fill me-2"></i> Draft
                    </div>
                @else
                    <div class="badge-status bg-secondary-subtle text-secondary d-flex align-items-center">
                        <i class="bi bi-archive-fill me-2"></i> Archived
                    </div>
                @endif

                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold mb-2 text-uppercase small">Details</h6>
                    <div class="small mb-2">
                        <span class="text-muted">Slug:</span><br>
                        <code class="text-primary">{{ $post->slug }}</code>
                    </div>
                    <div class="small mb-2">
                        <span class="text-muted">Created:</span><br>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    @if($post->published_at)
                    <div class="small">
                        <span class="text-muted">Published:</span><br>
                        <span>{{ $post->published_at->toDayDateTimeString() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
