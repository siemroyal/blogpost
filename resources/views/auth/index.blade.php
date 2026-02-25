@extends("layouts.app")
@section("title", "User Management")

@push("styles")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }

    /* Stats Cards */
    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Styling */
    .user-table-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .table thead th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #64748b;
        border-top: none;
        padding: 1rem 1.5rem;
    }
    .table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    /* Role Badges */
    .badge-role {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .role-admin { background: #fee2e2; color: #dc2626; }
    .role-editor { background: #e0e7ff; color: #4338ca; }
    .role-contributor { background: #fef3c7; color: #92400e; }
    .role-viewer { background: #f1f5f9; color: #475569; }

    /* Avatar */
    .avatar-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 10px;
        background: #f1f5f9;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endpush

@section("content")
<div class="container-fluid py-5">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h1 class="fw-bold h3 mb-1">User Management</h1>
            <p class="text-muted mb-0">Manage system access, roles, and account status.</p>
        </div>
        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-2"></i> Add New User
        </a>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-primary text-white me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-bold mb-0">Total Users</h6>
                    <h4 class="fw-bold mb-0">{{ $usersCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-success text-white me-3">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-bold mb-0">Active Now</h6>
                    <h4 class="fw-bold mb-0">{{ $activeCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-warning text-white me-3">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-bold mb-0">Admins</h6>
                    <h4 class="fw-bold mb-0">{{ $adminCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div class="icon-box bg-info text-white me-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-bold mb-0">New This Month</h6>
                    <h4 class="fw-bold mb-0">{{ $newUsersCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table & Filters -->
    <div class="user-table-card">
        <!-- Filter Bar -->
        <div class="p-4 border-bottom bg-white">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 shadow-none"
                               placeholder="Search name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select shadow-none">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="contributor">Contributor</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select shadow-none">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light w-100 fw-bold border">Filter</button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            @include("partials.message")
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>User Info</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/'.$user->profile_picture) }}" class="avatar-img me-3">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="avatar-img me-3">
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-role role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="text-success small fw-bold">
                                    <span class="status-indicator bg-success"></span> Active
                                </span>
                            @else
                                <span class="text-danger small fw-bold">
                                    <span class="status-indicator bg-danger"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}"><i class="bi bi-pencil me-2 text-primary"></i> Edit</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.show', $user->id) }}"><i class="bi bi-eye me-2 text-info"></i> View Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Archive this user?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No users found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-top bg-light">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
