@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Activity Log</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('activity-logs.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>User ID (optional)</label>
            <input type="number" name="user_id" class="form-control">
        </div>

        <div class="mb-3">
            <label>Action</label>
            <input type="text" name="action" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Subject Type</label>
            <input type="text" name="subject_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Subject ID</label>
            <input type="number" name="subject_id" class="form-control" required>
        </div>

        <button class="btn btn-success">Save Log</button>
        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
