<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $requests = MaintenanceRequest::all();
        return view('department.maintenance_requests.index', compact('requests'));
    }

    public function create()
    {
        return view('department.maintenance_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'property_location' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        MaintenanceRequest::create($request->all());
        return redirect()->route('department.maintenance_requests.index')->with('success', 'Request created successfully.');
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        return view('department.maintenance_requests.show', compact('maintenanceRequest'));
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        return view('department.maintenance_requests.edit', compact('maintenanceRequest'));
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $request->validate([
            'requester_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'property_location' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $maintenanceRequest->update($request->all());
        return redirect()->route('department.maintenance_requests.index')->with('success', 'Request updated successfully.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();
        return redirect()->route('department.maintenance_requests.index')->with('success', 'Request deleted successfully.');
    }
}