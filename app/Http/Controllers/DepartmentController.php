<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function dashboard(Request $request)
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

        $items = $query->get();
        
        // Add these lines to get request statistics
        $pendingRequests = auth()->user()->requests()->where('status', 'pending')->count();
        $approvedRequests = auth()->user()->requests()->where('status', 'approved')->count();
        $totalRequests = auth()->user()->requests()->count();

        return view('department.dashboard', compact('items', 'pendingRequests', 'approvedRequests', 'totalRequests'));
    }
} 