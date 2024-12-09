<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function pending()
    {
        $pendingRequests = InventoryRequest::where('status', 'pending')
            ->with(['deptUser.department', 'item'])
            ->latest()
            ->get();
            
        return view('admin.pending', compact('pendingRequests'));
    }

    public function approve($id)
    {
        $request = InventoryRequest::findOrFail($id);
        $request->update([
            'status' => 'approved',
            'approved_date' => now()
        ]);
        
        return back()->with('success', 'Request approved successfully');
    }
}