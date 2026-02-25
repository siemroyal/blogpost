@extends("layouts.app")
@section("title", "Edit User: " . $user->name)

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
    .user-card { background: white; border: 1px solid #e2e8f0; border-radius: 1.25rem; }
    .avatar-wrapper { position: relative; width: 120px; margin: 0 auto; }
    .avatar-preview { width: 120px; height: 120px; border-radius: 100%; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); overflow: hidden; }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-edit-btn { position: absolute; bottom: 0; right: 0; background: #4361ee; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff; }
    .role-card input[type="radio"] { display: none; }
    .role-card label { display: block; padding: 1rem; border: 2px solid #f1f5f9; border-radius: 0.75rem; cursor: pointer; transition: 0.2s; }
    .role-card input[type="radio"]:checked + label { border-color: #4361ee; background-color: #f0f7ff; }
</style>
@endpush

@section("content")
<div class="container py-5">

    <!-- ERROR DEBUG BLOCK -->
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-4 mb-4">
            <h6 class="fw-bold">Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- MAIN FORM (Note the ID) -->
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="updateUserForm">
        @csrf
        @method('PUT')

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="user-card p-4 p-md-5 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0 text-dark">Account Information</h4>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm rounded-pill px-3 text-muted">Back to List</a>
                    </div>

                    <div class="avatar-wrapper mb-5">
                        <div class="avatar-preview">
                            <img id="imagePreview" src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f1f5f9&color=cbd5e1' }}">
                        </div>
                        <label for="imageUpload" class="avatar-edit-btn"><i class="bi bi-camera"></i></label>
                        <input type='file' name="profile_picture" id="imageUpload" accept=".png, .jpg, .jpeg" class="d-none" />
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="alert alert-light border border-warning-subtle text-muted small p-3 rounded-4">
                                <i class="bi bi-info-circle me-2 text-warning"></i> Leave password fields empty if you don't want to change them.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="user-card p-4 mb-4 shadow-sm text-center">
                    <label class="form-label d-block fw-bold text-uppercase small text-muted mb-3">System Access</label>
                    <div class="form-check form-switch d-flex justify-content-center align-items-center gap-3 ps-0">
                        <span class="text-muted small">Suspended</span>
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="width: 3rem; height: 1.5rem; cursor: pointer;">
                        <span class="fw-bold text-success">Active</span>
                    </div>
                </div>

                <div class="user-card p-4 mb-4 shadow-sm">
                    <label class="form-label d-block fw-bold text-uppercase small text-muted mb-3">Assigned Role</label>
                    @foreach(['admin', 'editor', 'contributor', 'viewer'] as $role)
                    <div class="role-card mb-2">
                        <input type="radio" name="role" id="r_{{ $role }}" value="{{ $role }}" {{ old('role', $user->role) == $role ? 'checked' : '' }}>
                        <label for="r_{{ $role }}" class="small">
                            <span class="d-block fw-bold text-dark">{{ ucfirst($role) }}</span>
                            <span class="text-muted opacity-75">Permissions for {{ $role }} access.</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                        Update User Account
                    </button>

                    <!-- DELETE BUTTON triggers separate form -->
                    <button type="button" class="btn btn-link text-danger text-decoration-none w-100 mt-2" onclick="if(confirm('Delete permanently?')) document.getElementById('delete-form').submit();">
                        Delete User Permanently
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- SEPARATE DELETE FORM (OUTSIDE MAIN FORM) -->
    <form id="delete-form" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push("scripts")
<script>
    document.getElementById('imageUpload').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { document.getElementById('imagePreview').src = e.target.result; }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
