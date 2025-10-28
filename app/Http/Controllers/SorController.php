<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sor;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class SorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sor::with(['customer', 'product', 'users']);
        
        // If user is not admin, only show SORs assigned to them
        if (auth()->user()->role !== 'admin') {
            $query->whereHas('users', function($q) {
                $q->where('users.id', auth()->id());
            });
        }
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sor', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $sors = $query->paginate(10)->appends($request->query());
        return view('sors.index', compact('sors'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::where('status', 'active')->get();
        return view('sors.create', compact('customers', 'products', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sor' => 'required|string|unique:sors,sor',
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'init_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $sor = Sor::create($validated);
        
        // Attach assigned users
        if ($request->has('user_ids')) {
            $sor->users()->sync($request->user_ids);
        }

        return redirect()->route('sors.index')->with('success', 'SOR created successfully.');
    }

    public function show(Sor $sor)
    {
        // Check if user has access to this SOR
        if (auth()->user()->role !== 'admin' && !$sor->users->contains(auth()->id())) {
            abort(403, 'You do not have access to this SOR.');
        }
        
        $sor->load(['customer', 'product', 'dailyActivities', 'users']);
        return view('sors.show', compact('sor'));
    }

    public function edit(Sor $sor)
    {
        // Only admin can edit SORs
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can edit SORs.');
        }
        
        $customers = Customer::all();
        $products = Product::all();
        $users = User::where('status', 'active')->get();
        return view('sors.edit', compact('sor', 'customers', 'products', 'users'));
    }

    public function update(Request $request, Sor $sor)
    {
        $validated = $request->validate([
            'sor' => 'required|string|unique:sors,sor,' . $sor->id,
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'init_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $sor->update($validated);
        
        // Sync assigned users
        if ($request->has('user_ids')) {
            $sor->users()->sync($request->user_ids);
        } else {
            $sor->users()->sync([]);
        }

        return redirect()->route('sors.index')->with('success', 'SOR updated successfully.');
    }

    public function destroy(Sor $sor)
    {
        // Only admin can delete SORs
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admin can delete SORs.');
        }
        
        $sor->delete();
        return redirect()->route('sors.index')->with('success', 'SOR deleted successfully.');
    }
}
