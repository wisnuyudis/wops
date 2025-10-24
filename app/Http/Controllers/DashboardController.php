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
        
        // Get statistics - filtered by user role
        if ($user->role === 'admin') {
            $totalUsers = User::count();
            $totalSors = Sor::count();
            $totalActivities = DailyActivity::count();
            $totalWeeklyProgress = WeeklyProgress::count();
            
            // Get recent activities - all
            $recentActivities = DailyActivity::with(['user', 'sor'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
        } else {
            // For regular users, show only their own data
            $totalUsers = 1; // Only themselves
            $totalSors = Sor::count(); // SORs are visible to all
            $totalActivities = DailyActivity::where('user_id', $user->id)->count();
            $totalWeeklyProgress = WeeklyProgress::where('user_id', $user->id)->count();
            
            // Get recent activities - only user's own
            $recentActivities = DailyActivity::with(['user', 'sor'])
                ->where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('dashboard.index', compact(
            'totalUsers',
            'totalSors',
            'totalActivities',
            'totalWeeklyProgress',
            'recentActivities'
        ));
    }
}
