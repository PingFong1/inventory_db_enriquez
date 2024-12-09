<?php

namespace App\Http\Controllers;

use App\Models\Request as InventoryRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class DepartmentRequestController extends Controller
{
    public function index()
    {
        $requests = InventoryRequest::where('dept_user_id', auth()->id())
            ->with(['item', 'departments'])
            ->latest()
            ->paginate(10);
            
        $items = Item::where('status', 'available')->get();
            
        $categories = Item::where('status', 'available')
            ->distinct('category')
            ->pluck('category')
            ->mapWithKeys(function ($category) {
                return [$category => ucfirst(str_replace('_', ' ', $category))];
            })
            ->toArray();
            
        return view('department.requests.index', compact('requests', 'items', 'categories'));
    }

    public function create()
    {
        $items = Item::where('status', 'available')->get();
        return view('department.requests.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'needed_by' => 'required|date|after:today',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        foreach ($validated['items'] as $itemData) {
            InventoryRequest::create([
                'dept_user_id' => auth()->id(),
                'item_id' => $itemData['id'],
                'quantity' => $itemData['quantity'],
                'purpose' => $validated['purpose'],
                'needed_by' => $validated['needed_by'],
                'priority' => $validated['priority'],
                'status' => 'pending'
            ]);
        }

        return redirect()->route('department.requests.index')
            ->with('success', 'Requests submitted successfully');
    }

    public function show(InventoryRequest $request)
    {
        $request->load(['item', 'departments']);
        return view('department.requests.show', compact('request'));
    }

    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        // Add update logic if needed
    }

    public function destroy(InventoryRequest $request)
    {
        if ($request->status === 'pending') {
            $request->delete();
            return redirect()->route('department.requests.index')
                ->with('success', 'Request cancelled successfully');
        }

        return back()->with('error', 'Cannot cancel processed requests');
    }
} 