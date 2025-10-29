<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;


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
        // Normalize contact: remove spaces, hyphens and parentheses but keep leading + if present
        $rawContact = $request->input('contact');
        $normalized = $rawContact !== null ? preg_replace('/[\s\-\(\)]/', '', $rawContact) : null;
        $request->merge(['contact' => $normalized]);

        // Accept either: 0XXXXXXXXX (10 digits, leading 0) OR optional +94/94 followed by 9 digits
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => ['required', 'string', 'max:255', 'regex:/^(0\d{9}|\+?94\d{9})$/'],
            'address' => 'nullable|string|max:500',
            'town' => 'nullable|string|max:100',
        ], [
            'contact.regex' => 'Contact must be 10 digits starting with 0 (e.g. 0711234567) or include country code 94 with 9 subscriber digits (e.g. +94711234567).',
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
        $rawContact = $request->input('contact');
        $normalized = $rawContact !== null ? preg_replace('/[\s\-\(\)]/', '', $rawContact) : null;
        $request->merge(['contact' => $normalized]);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => ['required', 'string', 'max:255', 'regex:/^(0\d{9}|\+?94\d{9})$/'],
            'address' => 'nullable|string|max:500',
            'town' => 'nullable|string|max:100',
        ], [
            'contact.regex' => 'Contact must be 10 digits starting with 0 (e.g. 0711234567) or include country code 94 with 9 subscriber digits (e.g. +94711234567).',
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
