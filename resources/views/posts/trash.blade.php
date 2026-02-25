@extends('layouts.app')
@section('title', 'Trashed Posts')

@section('content')
<div class="container-xl">
    <h3 class="mb-4">🗑 Trashed Posts</h3>

    <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-3">
        ← Back to Posts
    </a>

    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Deleted At</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->deleted_at->diffForHumans() }}</td>
                <td class="text-end">
                    <!-- Restore -->
                    <form action="{{ route('posts.restore', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                        </button>
                    </form>

                    <!-- Force Delete -->
                    <form action="{{ route('posts.forceDelete', $post->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Permanently delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-x-circle"></i> Delete Forever
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No trashed posts found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection
