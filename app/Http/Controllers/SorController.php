<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sor;
use App\Models\Customer;
use App\Models\Product;

class SorController extends Controller
{
    public function index()
    {
        $sors = Sor::with(['customer', 'product'])->paginate(10);
        return view('sors.index', compact('sors'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sors.create', compact('customers', 'products'));
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
        ]);

        Sor::create($validated);

        return redirect()->route('sors.index')->with('success', 'SOR created successfully.');
    }

    public function show(Sor $sor)
    {
        $sor->load(['customer', 'product', 'dailyActivities']);
        return view('sors.show', compact('sor'));
    }

    public function edit(Sor $sor)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sors.edit', compact('sor', 'customers', 'products'));
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
        ]);

        $sor->update($validated);

        return redirect()->route('sors.index')->with('success', 'SOR updated successfully.');
    }

    public function destroy(Sor $sor)
    {
        $sor->delete();
        return redirect()->route('sors.index')->with('success', 'SOR deleted successfully.');
    }
}
