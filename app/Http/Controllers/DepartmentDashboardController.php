<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Request as InventoryRequest;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class DepartmentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::where('status', 'available');

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
        
        // Get request statistics
        $pendingRequests = 0;
        $approvedRequests = 0;
        $totalRequests = 0;

        if (auth()->guard('department')->check()) {
            $user = auth()->guard('department')->user();
            $pendingRequests = InventoryRequest::where('dept_user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $approvedRequests = InventoryRequest::where('dept_user_id', $user->id)
                ->where('status', 'approved')
                ->count();
            $totalRequests = InventoryRequest::where('dept_user_id', $user->id)
                ->count();
        }

        return view('department.dashboard', compact('items', 'pendingRequests', 'approvedRequests', 'totalRequests'));
    }
}