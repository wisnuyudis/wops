<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyActivity;
use App\Models\Sor;
use App\Models\JobType;
use App\Models\JobItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DailyActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyActivity::with(['user', 'sor', 'jobType', 'jobItem']);
        
        // Get selected month (default to current month)
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth . '-01');
        
        // Filter by selected month
        $query->whereYear('date', $date->year)
              ->whereMonth('date', $date->month);
        
        // Get users list for admin filter
        if (auth()->user()->role === 'admin') {
            $users = User::orderBy('name')->get();
            
            // Filter by user if selected
            if ($request->has('user_id') && $request->user_id != '') {
                $query->where('user_id', $request->user_id);
            }
        } else {
            // If user is not admin, only show their own activities
            $users = collect(); // Empty collection for non-admin
            $query->where('user_id', auth()->id());
        }
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('cust_name', 'like', "%{$search}%")
                  ->orWhere('product', 'like', "%{$search}%")
                  ->orWhere('objective', 'like', "%{$search}%")
                  ->orWhereHas('sor', function($q) use ($search) {
                      $q->where('sor', 'like', "%{$search}%");
                  });
            });
        }
        
        $activities = $query->orderBy('date', 'desc')
                           ->orderBy('id', 'desc')
                           ->paginate(10)
                           ->appends($request->query());
        
        return view('daily-activities.index', compact('activities', 'users', 'selectedMonth'));
    }

    public function create()
    {
        // Get only SORs assigned to current user (or all if admin)
        if (auth()->user()->role === 'admin') {
            $sors = Sor::where('status', 'active')->get();
        } else {
            $sors = auth()->user()->sors()->where('status', 'active')->get();
        }
        
        $jobTypes = JobType::all();
        $jobItems = JobItem::all();
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('daily-activities.create', compact('sors', 'jobTypes', 'jobItems', 'customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'sor_id' => 'nullable|exists:sors,id',
            'action' => 'nullable|string',
            'cust_name' => 'nullable|string',
            'pic' => 'nullable|string',
            'product' => 'nullable|string',
            'job_type_id' => 'nullable|exists:job_types,id',
            'job_item_id' => 'nullable|exists:job_items,id',
            'objective' => 'nullable|string',
            'result_of_issue' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
        ]);

        $validated['user_id'] = auth()->id();
        
        DailyActivity::create($validated);

        return redirect()->route('daily-activities.index')->with('success', 'Daily Activity created successfully.');
    }

    public function show(DailyActivity $dailyActivity)
    {
        $dailyActivity->load(['user', 'sor', 'jobType', 'jobItem']);
        return view('daily-activities.show', compact('dailyActivity'));
    }

    public function edit(DailyActivity $dailyActivity)
    {
        // Only allow users to edit their own activities unless admin
        if (auth()->user()->role !== 'admin' && $dailyActivity->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get only SORs assigned to current user (or all if admin)
        if (auth()->user()->role === 'admin') {
            $sors = Sor::where('status', 'active')->get();
        } else {
            $sors = auth()->user()->sors()->where('status', 'active')->get();
        }
        
        $jobTypes = JobType::all();
        $jobItems = JobItem::all();
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('daily-activities.edit', compact('dailyActivity', 'sors', 'jobTypes', 'jobItems', 'customers', 'products'));
    }

    public function update(Request $request, DailyActivity $dailyActivity)
    {
        // Only allow users to edit their own activities unless admin
        if (auth()->user()->role !== 'admin' && $dailyActivity->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'date' => 'required|date',
            'sor_id' => 'nullable|exists:sors,id',
            'action' => 'nullable|string',
            'cust_name' => 'nullable|string',
            'pic' => 'nullable|string',
            'product' => 'nullable|string',
            'job_type_id' => 'nullable|exists:job_types,id',
            'job_item_id' => 'nullable|exists:job_items,id',
            'objective' => 'nullable|string',
            'result_of_issue' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,on_hold',
        ]);

        $dailyActivity->update($validated);

        return redirect()->route('daily-activities.index')->with('success', 'Daily Activity updated successfully.');
    }

    public function destroy(DailyActivity $dailyActivity)
    {
        // Only allow users to delete their own activities unless admin
        if (auth()->user()->role !== 'admin' && $dailyActivity->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $dailyActivity->delete();
        return redirect()->route('daily-activities.index')->with('success', 'Daily Activity deleted successfully.');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'sor_id' => 'nullable|exists:sors,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        // Get user name for filename
        $user = User::findOrFail($validated['user_id']);
        $userName = str_replace(' ', '_', $user->name);
        
        // Format dates for filename
        $dateFrom = Carbon::parse($validated['date_from'])->format('Ymd');
        $dateTo = Carbon::parse($validated['date_to'])->format('Ymd');
        
        // Generate filename
        $filename = "DailyActivity_{$userName}_{$dateFrom}-{$dateTo}.xlsx";
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DailyActivityExport(
                $validated['user_id'],
                $validated['sor_id'] ?? null,
                $validated['date_from'],
                $validated['date_to']
            ),
            $filename
        );
    }
    
    public function getUserSors(User $user)
    {
        // Get SORs assigned to the user with customer relationship
        $sors = $user->sors()->with('customer')->orderBy('sor')->get();
        
        return response()->json([
            'sors' => $sors
        ]);
    }
}
