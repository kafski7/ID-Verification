<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Staff;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'active_staff'    => Staff::where('status', 'ACTIVE')->count(),
            'inactive_staff'  => Staff::where('status', 'INACTIVE')->count(),
            'scans_today'     => ScanLog::whereDate('scanned_at', today())->count(),
            'valid_today'     => ScanLog::whereDate('scanned_at', today())->where('result', 'VALID')->count(),
        ];

        $recentScans = ScanLog::with('staff')
            ->orderByDesc('scanned_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentScans'));
    }
}
