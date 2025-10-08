<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        // Accept phone numbers in international or local formats: digits, spaces, +, -, ()
        $phoneRule = ['required', 'string', 'max:255', 'regex:/^[0-9+()\-\s]+$/'];

        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => $phoneRule,
            'address' => 'nullable|string|max:500',
        ], [
            'contact.regex' => 'The contact number may contain only digits, spaces, parentheses, plus and hyphens.',
        ]);

        Supplier::create($request->all());

        // Fixed route name
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $phoneRule = ['required', 'string', 'max:255', 'regex:/^[0-9+()\-\s]+$/'];

        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => $phoneRule,
            'address' => 'nullable|string|max:500',
        ], [
            'contact.regex' => 'The contact number may contain only digits, spaces, parentheses, plus and hyphens.',
        ]);

        $supplier->update($request->all());

        // Fixed route name
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        // Fixed route name
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}