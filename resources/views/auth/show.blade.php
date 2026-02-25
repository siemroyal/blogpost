@extends("layouts.app")
@section("title", "User Profile: " . $user->name)

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .profile-header { background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%); height: 200px; border-radius: 1.5rem 1.5rem 0 0; }
    .profile-card { background: white; border-radius: 1.5rem; border: 1px solid #e2e8f0; margin-top: -100px; overflow: hidden; }
    .profile-avatar { width: 140px; height: 140px; border-radius: 2rem; border: 6px solid #fff; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #94a3b8; }
    .info-value { font-weight: 600; color: #1e293b; font-size: 1.1rem; }

    .badge-status { padding: 0.5rem 1.2rem; border-radius: 2rem; font-weight: 700; font-size: 0.85rem; }
</style>
@endpush

@section("content")
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('users.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
                    <i class="bi bi-arrow-left me-2"></i> Back to Users
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-pencil me-2"></i> Edit Account
                    </a>
                </div>
            </div>

            <!-- Profile Canvas -->
            <div class="profile-header"></div>
            <div class="profile-card shadow-sm p-4 p-md-5">
                <div class="row align-items-end mb-5">
                    <div class="col-auto">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="{{ $user->name }}"
                            class="profile-avatar" title="{{ $user->name }}'s Profile Picture">
                        @else
                            <img src="{{ asset('images/no-image.jpg')}} " alt="no image" class="profile-avatar">
                        @endif
                    </div>
                    <div class="col">
                        <h1 class="fw-bold mb-1">{{ $user->name }}</h1>
                        <p class="text-muted fs-5 mb-2">{{ $user->email }}</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">
                                <i class="bi bi-shield-check me-1"></i> {{ ucfirst($user->role) }}
                            </span>
                            @if($user->is_active)
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-circle-fill me-1 small"></i> Active Account
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-circle-fill me-1 small"></i> Suspended
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="info-label d-block mb-1">Account Role</label>
                            <div class="info-value">{{ ucfirst($user->role) }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="info-label d-block mb-1">Status</label>
                            <div class="info-value">{{ $user->is_active ? 'Fully Active' : 'Restricted' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="info-label d-block mb-1">Registration Date</label>
                            <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="info-label d-block mb-1">Email Verified</label>
                            <div class="info-value">
                                @if($user->email_verified_at)
                                    <span class="text-success"><i class="bi bi-patch-check"></i> Verified</span>
                                @else
                                    <span class="text-muted">Not Verified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="info-label d-block mb-1">Last Updated</label>
                            <div class="info-value">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Footer -->
                <div class="mt-5 pt-5 border-top">
                    <h6 class="fw-bold text-muted small text-uppercase mb-4">Technical Details</h6>
                    <div class="row">
                        <div class="col-auto">
                            <div class="p-3 bg-light rounded-4 text-center">
                                <span class="d-block text-muted small">User ID</span>
                                <span class="fw-bold">#{{ $user->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
