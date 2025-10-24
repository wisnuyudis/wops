<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sor;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class SorController extends Controller
{
    public function index()
    {
        $sors = Sor::with(['customer', 'product', 'users'])->paginate(10);
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
        $sor->load(['customer', 'product', 'dailyActivities', 'users']);
        return view('sors.show', compact('sor'));
    }

    public function edit(Sor $sor)
    {
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
        $sor->delete();
        return redirect()->route('sors.index')->with('success', 'SOR deleted successfully.');
    }
}
