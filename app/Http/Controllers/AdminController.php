<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\InventoryRequest;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Department;
use App\Models\Activity;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogExport;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $notifications = collect([
            (object)[
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => 'Several items are running low on stock.'
            ],
            // Add more notifications as needed
        ]);

        $totalItems = Item::count();
        $lowStockItems = Item::whereRaw('current_quantity < minimum_quantity AND current_quantity > 0')->count();
        $outOfStockItems = Item::where('current_quantity', '<=', 0)->count();
        $inStockItems = Item::where('current_quantity', '>', 0)->count();
        $activeDepartments = Department::where('status', 'active')->count();
        
        $totalValue = Item::sum(DB::raw('current_quantity * unit_price'));
        
        $lowStockValue = Item::whereRaw('current_quantity < minimum_quantity')
            ->sum(DB::raw('current_quantity * unit_price'));

        $startOfMonth = Carbon::now()->startOfMonth();
        $monthlyTurnover = StockMovement::where('type', 'out')
            ->where('stock_movements.created_at', '>=', $startOfMonth)
            ->join('items', 'stock_movements.item_id', '=', 'items.id')
            ->sum(DB::raw('stock_movements.quantity * items.unit_price'));

        $recentMovements = StockMovement::with(['item', 'admin'])
            ->latest()
            ->take(5)
            ->get();

        $departmentStats = Department::withCount(['items as total_items'])
            ->withCount(['items as low_stock' => function($query) {
                $query->whereRaw('current_quantity < minimum_quantity');
            }])
            ->get();

        $stockTrends = $this->getStockTrends();

        $departmentDistribution = $this->getDepartmentDistribution();

        // Get activity logs
        $activityLogs = Activity::with(['admin', 'item', 'department'])
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact(
            'totalItems',
            'lowStockItems',
            'outOfStockItems',
            'inStockItems',
            'activeDepartments',
            'totalValue',
            'lowStockValue',
            'monthlyTurnover',
            'recentMovements',
            'departmentStats',
            'stockTrends',
            'departmentDistribution',
            'notifications',
            'activityLogs'
        ));
    }

    private function getStockTrends()
    {
        $months = collect([]);
        $stockLevels = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M'));

            $avgStock = StockMovement::whereYear('stock_movements.created_at', $date->year)
                ->whereMonth('stock_movements.created_at', $date->month)
                ->select(DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as net_change'))
                ->first();

            $stockLevels->push($avgStock ? $avgStock->net_change : 0);
        }

        return [
            'labels' => $months,
            'data' => $stockLevels
        ];
    }

    private function getDepartmentDistribution()
    {
        return Department::withCount('items')
            ->get()
            ->map(function ($dept) {
                return [
                    'name' => $dept->name,
                    'count' => $dept->items_count
                ];
            });
    }

    public function notifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return view('admin.notifications', compact('notifications'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $items = Item::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->orWhere('department', 'like', "%{$query}%")
            ->take(10)
            ->get();
            
        $departments = Department::where('name', 'like', "%{$query}%")
            ->take(5)
            ->get();

        return view('admin.search-results', compact('items', 'departments', 'query'));
    }

    public function filterDashboard(Request $request)
    {
        $filter = $request->type;
        
        $data = match($filter) {
            'low-stock' => $this->getLowStockData(),
            'out-of-stock' => $this->getOutOfStockData(),
            'high-value' => $this->getHighValueData(),
            default => $this->getAllData(),
        };
        
        return response()->json($data);
    }

    private function getActivityLogs()
    {
        return ActivityLog::with('user')
            ->latest()
            ->paginate(10);
    }

    public function exportActivityLog()
    {
        $logs = ActivityLog::with('user')->get();
        
        return Excel::download(new ActivityLogExport($logs), 'activity-log.xlsx');
    }
}