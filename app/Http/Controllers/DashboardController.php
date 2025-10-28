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
            ->select('cust_name', DB::raw('count(*) as total'))
            ->whereNotNull('cust_name')
            ->groupBy('cust_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Get Daily Activity by Product (for Bar Chart)
        $activityByProducts = (clone $query)
            ->select('product', DB::raw('count(*) as total'))
            ->whereNotNull('product')
            ->groupBy('product')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Get Daily Activity by Job Items (for Line Chart - by day)
        $activityByJobItems = (clone $query)
            ->join('job_items', 'daily_activities.job_item_id', '=', 'job_items.id')
            ->select('job_items.name', DB::raw('DAY(date) as day'), DB::raw('count(*) as total'))
            ->groupBy('job_items.name', DB::raw('DAY(date)'))
            ->orderBy(DB::raw('DAY(date)'))
            ->get();
        
        // Transform job items data for Chart.js
        $jobItemsData = [];
        $days = range(1, $date->daysInMonth);
        
        foreach ($activityByJobItems->groupBy('name') as $jobItemName => $items) {
            $dailyData = array_fill(0, count($days), 0);
            foreach ($items as $item) {
                $dailyData[$item->day - 1] = $item->total;
            }
            $jobItemsData[$jobItemName] = $dailyData;
        }
        
        return view('dashboard.index', compact(
            'selectedMonth',
            'users',
            'selectedUserId',
            'activityByCustomers',
            'activityByProducts',
            'jobItemsData',
            'days'
        ));
    }
}
