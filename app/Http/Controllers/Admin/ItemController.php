<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // Apply search filter
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Apply category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Apply status filter
        if ($request->has('status')) {
            switch ($request->status) {
                case 'in_stock':
                    $query->where('current_quantity', '>', DB::raw('minimum_quantity'));
                    break;
                case 'low_stock':
                    $query->where('current_quantity', '<=', DB::raw('minimum_quantity'))
                          ->where('current_quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('current_quantity', 0);
                    break;
            }
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'quantity_asc':
                    $query->orderBy('current_quantity', 'asc');
                    break;
                case 'quantity_desc':
                    $query->orderBy('current_quantity', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $items = $query->paginate(10);
        
        // Get statistics
        $totalItems = Item::count();
        $lowStockItems = Item::where('current_quantity', '<=', DB::raw('minimum_quantity'))->count();
        $inStockItems = Item::where('current_quantity', '>', DB::raw('minimum_quantity'))->count();

        return view('admin.items.index', compact('items', 'totalItems', 'lowStockItems', 'inStockItems'));
    }

    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string',
                'department' => 'required|string',
                'current_quantity' => 'required|integer|min:0',
                'minimum_quantity' => 'required|integer|min:0',
                'maximum_quantity' => 'required|integer|min:0|gte:minimum_quantity',
                'usage_frequency' => 'required|string',
                'budget_category' => 'required|string',
                'unit_price' => 'required|numeric|min:0',
                'unit_type' => 'required|string',
                'sku' => 'required|string|unique:items',
                'status' => 'required|string',
                'barcode' => 'nullable|string|unique:items',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Generate barcode if not provided
            if (empty($validated['barcode'])) {
                $validated['barcode'] = 'ITM-' . strtoupper(uniqid());
            }

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('items', 'public');
            }

            $item = Item::create($validated);
            
            // Automatically update status based on quantities
            $item->updateStatus();

            return redirect()->route('admin.items.index')
                ->with('success', 'Item created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating item: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error creating item: ' . $e->getMessage()]);
        }
    }

    public function show(Item $item)
    {
        $stockMovements = $item->stockMovements()->with('admin')->latest()->paginate(10);
        return view('admin.items.show', compact('item', 'stockMovements'));
    }

    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string',
                'department' => 'required|string',
                'current_quantity' => 'required|integer|min:0',
                'minimum_quantity' => 'required|integer|min:0',
                'maximum_quantity' => 'required|integer|min:0|gte:minimum_quantity',
                'usage_frequency' => 'required|string',
                'budget_category' => 'required|string',
                'unit_price' => 'required|numeric|min:0',
                'unit_type' => 'required|string',
                'sku' => 'required|string|unique:items,sku,' . $item->id,
                'status' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $validated['image'] = $request->file('image')->store('items', 'public');
            }

            $item->update($validated);
            
            // Automatically update status based on quantities
            $item->updateStatus();

            return redirect()->route('admin.items.index')
                ->with('success', 'Item updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error updating item: ' . $e->getMessage()]);
        }
    }

    public function destroy(Item $item)
    {
        if ($item->image_path) {
            Storage::delete('public/' . $item->image_path);
        }
        Storage::delete('public/barcodes/'.$item->barcode.'.png');
        
        $item->delete();
        return back()->with('success', 'Item deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            // Optional: Add validation
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items selected'
                ]);
            }

            // Optional: Add authorization check
            // $this->authorize('delete', Item::class);

            Item::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' items deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting items: ' . $e->getMessage()
            ]);
        }
    }

    public function stockIn(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        $item->stockIn($validated['quantity'], $validated['remarks']);

        return back()->with('success', 'Stock added successfully');
    }

    public function stockOut(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        if ($item->stockOut($validated['quantity'], $validated['remarks'])) {
            return back()->with('success', 'Stock deducted successfully');
        }

        return back()->with('error', 'Insufficient stock');
    }

    public function showScanForm()
    {
        return view('admin.items.scan');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $item = Item::where('barcode', $request->barcode)->first();

        if (!$item) {
            return back()->with('error', 'Item not found');
        }

        return redirect()->route('admin.items.show', $item->id)
            ->with('success', 'Item found successfully');
    }

    public function incrementQuantity(Item $item)
    {
        if ($item->current_quantity < $item->maximum_quantity) {
            $item->increment('current_quantity');
            $item->updateStatus();
            return response()->json(['success' => true]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Maximum quantity reached'
        ]);
    }

    public function decrementQuantity(Item $item)
    {
        if ($item->current_quantity > 0) {
            $item->decrement('current_quantity');
            $item->updateStatus();
            return response()->json(['success' => true]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Quantity cannot be less than 0'
        ]);
    }
}