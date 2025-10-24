<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyActivity;
use App\Models\WeeklyProgress;
use App\Models\User;
use App\Models\Sor;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics
        $totalUsers = User::count();
        $totalSors = Sor::count();
        $totalActivities = DailyActivity::count();
        $totalWeeklyProgress = WeeklyProgress::count();
        
        // Get recent activities
        $recentActivities = DailyActivity::with(['user', 'sor'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact(
            'totalUsers',
            'totalSors',
            'totalActivities',
            'totalWeeklyProgress',
            'recentActivities'
        ));
    }
}
