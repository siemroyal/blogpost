<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(10);
        return view('activity_logs.index', compact('logs'));
    }

    public function create()
    {
        return view('activity_logs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'action' => 'required|string',
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
        ]);

        ActivityLog::create($request->all());

        return redirect()->route('activity-logs.index')
                         ->with('success', 'Activity log created successfully.');
    }
}
