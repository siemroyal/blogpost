@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Activity Logs</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Subject Type</th>
                <th>Subject ID</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->user?->name ?? 'N/A' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ class_basename($log->subject_type) }}</td>
                    <td>{{ $log->subject_id }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No activity logs found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $logs->links() }}
</div>
@endsection
