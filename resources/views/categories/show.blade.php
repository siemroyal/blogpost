@extends("layouts.app")
@section("title", "Category Details: " . $category->name)
@section("content")
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Header & Actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-gray-800">Category Details</h2>
                <div>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-edit"></i> Edit Category
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Column: Image -->
                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4 border-end">
                            @if($category->image)
                                <img src="{{ $category->image && file_exists(public_path('storage/' . $category->image))
                                     ? asset('storage/' . $category->image)
                                     : asset('images/no-image.jpg') }}"
                                     alt="{{ $category->name }}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 300px; object-fit: cover;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-5x mb-3 opacity-25"></i>
                                    <p>No Image Available</p>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Details -->
                        <div class="col-md-8 p-4">
                            <div class="mb-4">
                                <label class="text-uppercase text-muted small fw-bold">Name</label>
                                <h3 class="fw-bold">{{ $category->name }}</h3>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <label class="text-uppercase text-muted small fw-bold">Slug</label>
                                    <p class="font-monospace text-primary">{{ $category->slug }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-uppercase text-muted small fw-bold">Status</label>
                                    <div>
                                        @if($category->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-uppercase text-muted small fw-bold">Parent Category</label>
                                <p>
                                    @if($category->parent)
                                        <span class="badge border text-dark bg-light">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-muted italic">None (This is a Root Category)</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="text-uppercase text-muted small fw-bold">Description</label>
                                <p class="text-secondary">
                                    {{ $category->description ?? 'No description provided for this category.' }}
                                </p>
                            </div>

                            <hr>

                            <div class="row text-muted small">
                                <div class="col-6">
                                    <strong>Created:</strong> {{ $category->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="col-6 text-end">
                                    <strong>Last Updated:</strong> {{ $category->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Footer for Delete Action -->
                <div class="card-footer bg-white text-end py-3">
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
