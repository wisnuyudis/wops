<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyProgress;
use Carbon\Carbon;

class WeeklyProgressController extends Controller
{
    public function index(Request $request)
    {
        $query = WeeklyProgress::with('user');
        
        // If user is not admin, only show their own progress
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        
        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('year', 'like', "%{$search}%")
                  ->orWhere('week_number', 'like', "%{$search}%")
                  ->orWhere('last_week_status', 'like', "%{$search}%")
                  ->orWhere('p1', 'like', "%{$search}%")
                  ->orWhere('p2', 'like', "%{$search}%")
                  ->orWhere('p3', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $weeklyProgresses = $query->orderBy('year', 'desc')
                                ->orderBy('week_number', 'desc')
                                ->paginate(10)
                                ->appends($request->query());
        return view('weekly-progress.index', compact('weeklyProgresses'));
    }

    public function create()
    {
        $currentYear = Carbon::now()->year;
        $currentWeek = Carbon::now()->week;
        
        // Check if user already has entry for current week
        $existingEntry = WeeklyProgress::where('user_id', auth()->id())
                                      ->where('year', $currentYear)
                                      ->where('week_number', $currentWeek)
                                      ->first();
        
        if ($existingEntry) {
            return redirect()->route('weekly-progress.index')
                           ->with('info', 'You already have an entry for this week (Week ' . $currentWeek . '). Please edit it from the list.');
        }
        
        return view('weekly-progress.create', compact('currentYear', 'currentWeek'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'week_number' => 'required|integer|min:1|max:53',
            'last_week_status' => 'nullable|string',
            'p1' => 'nullable|string',
            'p2' => 'nullable|string',
            'p3' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        
        // Check if entry already exists for this user, year, and week
        $existing = WeeklyProgress::where('user_id', auth()->id())
                                 ->where('year', $validated['year'])
                                 ->where('week_number', $validated['week_number'])
                                 ->first();
        
        if ($existing) {
            return redirect()->route('weekly-progress.edit', $existing)
                           ->with('error', 'Entry for this week already exists. Please edit it instead.');
        }
        
        WeeklyProgress::create($validated);

        return redirect()->route('weekly-progress.index')->with('success', 'Weekly Progress created successfully.');
    }

    public function show(WeeklyProgress $weeklyProgress)
    {
        $weeklyProgress->load('user');
        return view('weekly-progress.show', compact('weeklyProgress'));
    }

    public function edit(WeeklyProgress $weeklyProgress)
    {
        // Only allow users to edit their own progress unless admin
        if (auth()->user()->role !== 'admin' && $weeklyProgress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('weekly-progress.edit', compact('weeklyProgress'));
    }

    public function update(Request $request, WeeklyProgress $weeklyProgress)
    {
        // Only allow users to edit their own progress unless admin
        if (auth()->user()->role !== 'admin' && $weeklyProgress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'last_week_status' => 'nullable|string',
            'p1' => 'nullable|string',
            'p2' => 'nullable|string',
            'p3' => 'nullable|string',
        ]);

        $weeklyProgress->update($validated);

        return redirect()->route('weekly-progress.index')->with('success', 'Weekly Progress updated successfully.');
    }

    public function destroy(WeeklyProgress $weeklyProgress)
    {
        // Only allow users to delete their own progress unless admin
        if (auth()->user()->role !== 'admin' && $weeklyProgress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $weeklyProgress->delete();
        return redirect()->route('weekly-progress.index')->with('success', 'Weekly Progress deleted successfully.');
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'week_from' => 'required|integer|min:1|max:53',
            'week_to' => 'required|integer|min:1|max:53|gte:week_from',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $weekFrom = $validated['week_from'];
        $weekTo = $validated['week_to'];
        $year = $validated['year'];
        
        // Generate filename
        $filename = "WeeklyProgress_Week{$weekFrom}-Week{$weekTo}_{$year}.xlsx";
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\WeeklyProgressExport($weekFrom, $weekTo, $year),
            $filename
        );
    }
}
