@extends("layouts.app")
@section("title", "Create Category")
@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    body {
                background-color: #f8f9fa; /* Light gray background */
            }
            .container-xl { /* Use container-xl for a wider, but still contained, feel */
                max-width: 1300px;
            }
            .header-section {
                padding: 40px 0;
                border-bottom: 1px solid #e9ecef;
                margin-bottom: 30px;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,.04);
            }
            .action-bar {
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,.06);
                margin-bottom: 30px;
            }
            .post-table-container {
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,.06);
                overflow: hidden; /* For table-responsive */
            }
            .table th, .table td {
                padding: 1rem; /* More padding for spacious feel */
                border-top: none; /* Remove default table borders */
                border-bottom: 1px solid #e9ecef; /* Custom subtle border */
            }
            .table thead th {
                background-color: #f1f3f5;
                font-weight: 600;
                color: #495057;
            }
            .table tbody tr:last-child td {
                border-bottom: none;
            }
            .table-hover tbody tr:hover {
                background-color: #f6f8fa; /* Lighter hover */
            }
            .badge {
                padding: .5em .75em;
                font-size: 0.85em;
                font-weight: 500;
                border-radius: 5px;
            }
            .btn-icon-only {
                padding: .375rem .5rem;
            }
        </style>
@endpush
@push("scripts")

@endpush
@section("content")
<div class="container-fluid">
    <header class="header-section">
        <div class="d-flex justify-content-between align-items-center container-xl">
            <h1 class="mb-0 text-dark fw-bold">Posts</h1>
            <a href="{{ route('posts.create') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                <i class="bi bi-plus-lg me-2"></i> New Post
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('posts.trash') }}" class="btn btn-outline-danger position-relative">
                    <i class="bi bi-trash"></i>
                    @if($trashCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $trashCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> New Post
                </a>
            </div>
        </div>
    </header>
    <div class="action-bar mb-4">
        <div class="row g-3 align-items-center">
            <form method="GET" action="{{ route('posts.index') }}">
                <div class="action-bar mb-4">
                    <div class="row g-3 align-items-center">
                    <!-- 🔍 Search -->
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control border-start-0"
                                    placeholder="Search by title or content..."
                                >
                            </div>
                        </div>

                        <!-- 📜 Status Filter -->
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Filter by Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <!-- 🏷️ Category Filter -->
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Filter by Category</option>
                                @foreach($categories as $cat)
                                    <option
                                        value="{{ $cat->id }}"
                                        {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 🔄 Reset Button -->
                        <div class="col-md-1 text-end">
                            <a href="{{ route('posts.index') }}"
                               class="btn btn-outline-secondary btn-icon-only"
                               title="Reset Filters">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="post-table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 5%;">#</th>
                        <th scope="col" style="width: 30%;">Title</th>
                        <th scope="col" style="width: 15%;">Author</th>
                        <th scope="col" style="width: 15%;">Category</th>
                        <th scope="col" style="width: 10%;">Status</th>
                        <th scope="col" style="width: 15%;">Published At</th>
                        <th scope="col" style="width: 10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td><a href="{{route('posts.show',$post->id)}}" class="text-dark fw-semibold text-decoration-none">{{$post->title}}</a></td>
                            <td>{{$post->user->name}}</td>
                            <td><span class="badge bg-primary">{{$post->category->name}}</span></td>
                            <td><span class="badge bg-success">{{$post->status}}</span></td>
                            <td>{{$post->published_at}}</td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{route('posts.edit',$post->id)}}" class="btn btn-sm btn-link text-decoration-none" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{route('posts.show',$post->id)}}" class="btn btn-sm btn-link text-decoration-none text-info" title="View"><i class="bi bi-eye"></i></a>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none text-danger"
                                    title="Delete" data-bs-toggle="modal" data-bs-target="#deletePostModal-{{$post->id}}">
                                    <i class="bi bi-trash"></i></button>
                                    <form action="{{ route('posts.destroy',$post->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Move to trash?')"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        {{-- Delete Modals (can be generic and populated by JS) --}}
                        <div class="modal fade" id="deletePostModal-{{$post->id}}" tabindex="-1" aria-labelledby="deletePostModalLabel-{{$post->id}}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deletePostModalLabel-{{$post->id}}">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete {{ $post->title }}? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('posts.destroy',$post->id) }}" method="post">
                                            @csrf
                                            @method("DELETE")
                                            <input type="submit" value="Delete" class="btn btn-danger">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation" class="p-3">
            <ul class="pagination justify-content-center mb-0">
                {{ $posts->links() }}
            </ul>
        </nav>
    </div>
</div>
@endsection
