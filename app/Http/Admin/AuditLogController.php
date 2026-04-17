<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(20);

        return view('admin.audit-logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return view('admin.audit-logs.show', compact('log'));
    }
}