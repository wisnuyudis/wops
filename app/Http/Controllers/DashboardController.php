<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyActivity;
use App\Models\WeeklyProgress;
use App\Models\User;
use App\Models\Sor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get selected month and year (default to current month)
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth . '-01');
        
        // Get selected user for admin
        $selectedUserId = null;
        $users = null;
        if ($user->role === 'admin') {
            $users = User::orderBy('name')->get();
            $selectedUserId = $request->input('user_id', null);
        } else {
            $selectedUserId = $user->id;
        }
        
        // Base query for activities
        $query = DailyActivity::whereYear('date', $date->year)
            ->whereMonth('date', $date->month);
        
        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }
        
        // Get Daily Activity by Customers (for Pie Chart)
        $activityByCustomers = (clone $query)
            ->select(DB::raw('ISNULL(cust_name, \'Uncategorized\') as cust_name'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('ISNULL(cust_name, \'Uncategorized\')'))
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Get Daily Activity by Product (for Bar Chart)
        $activityByProducts = (clone $query)
            ->select(DB::raw('ISNULL(product, \'Uncategorized\') as product'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('ISNULL(product, \'Uncategorized\')'))
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Get Daily Activity by Job Items (for Pie Chart)
        $activityByJobItems = (clone $query)
            ->leftJoin('job_items', 'daily_activities.job_item_id', '=', 'job_items.id')
            ->select(DB::raw('ISNULL(job_items.name, \'Uncategorized\') as name'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('ISNULL(job_items.name, \'Uncategorized\')'))
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.index', compact(
            'selectedMonth',
            'users',
            'selectedUserId',
            'activityByCustomers',
            'activityByProducts',
            'activityByJobItems'
        ));
    }
}
