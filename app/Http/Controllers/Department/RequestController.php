<?php

namespace App\Http\Controllers\Department;

use App\Models\Item;
use App\Models\Request as ItemRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $requests = ItemRequest::where('dept_user_id', $user->id)
            ->with(['item'])
            ->latest()
            ->paginate(10);

        // Fetch all available items for the request form
        $items = Item::where('status', 'available')
            ->where('current_quantity', '>', 0)
            ->get();

        // Get unique categories from available items
        $categories = Item::where('status', 'available')
            ->where('current_quantity', '>', 0)
            ->distinct('category')
            ->pluck('category')
            ->mapWithKeys(function ($category) {
                return [$category => ucfirst(str_replace('_', ' ', $category))];
            })
            ->toArray();

        return view('department.requests.index', compact('requests', 'items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'needed_by' => 'required|date|after:today',
        ]);

        ItemRequest::create([
            'dept_user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'needed_by' => $validated['needed_by'],
            'status' => 'pending'
        ]);

        return redirect()->route('department.requests.index')
            ->with('success', 'Request submitted successfully');
    }

    public function getItems()
    {
        $items = Item::where('status', 'available')
            ->where('current_quantity', '>', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'quantity' => $item->current_quantity,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'unit_type' => $item->unit_type
                ];
            });

        return response()->json($items);
    }
}
