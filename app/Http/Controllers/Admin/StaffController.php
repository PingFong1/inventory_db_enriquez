<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Department;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('department')->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $departments = Department::where('status', 'active')
            ->where('staff_count', '<', 2)
            ->get();
        
        $roles = [
            'department_head' => 'Department Head',
            'staff' => 'Staff Member'
        ];
        
        return view('admin.staff.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'employee_id' => 'required|string|unique:staff,employee_id',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:department_head,staff',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::beginTransaction();
            
            $staff = Staff::create([
                ...$validated,
                'password' => Hash::make($validated['password']),
                'status' => 'active'
            ]);

            // Log activity
            Activity::create([
                'admin_id' => auth()->id(),
                'action_type' => 'create', 
                'description' => "Created new staff member: {$staff->name}",
                'department_id' => $staff->department_id
            ]);

            DB::commit();
            
            return redirect()
                ->route('admin.staff.index')
                ->with('success', 'Staff member created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create staff member. Please try again.']);
        }
    }

    public function edit(Staff $staff)
    {
        $departments = Department::where('status', 'active')->get();
        $roles = [
            'department_head' => 'Department Head',
            'inventory_manager' => 'Inventory Manager',
            'staff' => 'Staff Member'
        ];
        
        return view('admin.staff.edit', compact('staff', 'departments', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        // If changing departments
        if ($staff->department_id != $request->department_id) {
            $newDepartment = Department::find($request->department_id);
            
            // Check new department's staff limit
            if ($newDepartment->staff_count >= 2) {
                return back()->withErrors([
                    'department_id' => 'Selected department has reached its maximum staff limit (2 members).'
                ])->withInput();
            }

            // Check department head if applicable
            if ($request->role === 'department_head') {
                $existingHead = Staff::where('department_id', $request->department_id)
                    ->where('role', 'department_head')
                    ->exists();
                    
                if ($existingHead) {
                    return back()->withErrors([
                        'role' => 'Selected department already has a department head.'
                    ])->withInput();
                }
            }

            // Update staff counts
            $staff->department->decrement('staff_count');
            $newDepartment->increment('staff_count');
        }
        // If just changing role within same department
        elseif ($staff->role != $request->role && $request->role === 'department_head') {
            $existingHead = Staff::where('department_id', $staff->department_id)
                ->where('role', 'department_head')
                ->where('id', '!=', $staff->id)
                ->exists();
                
            if ($existingHead) {
                return back()->withErrors([
                    'role' => 'This department already has a department head.'
                ])->withInput();
            }
        }

        // Continue with regular validation and update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'employee_id' => 'required|unique:staff,employee_id,' . $staff->id,
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:department_head,staff',
            'status' => 'required|in:active,inactive',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully');
    }

    public function destroy(Staff $staff)
    {
        // Decrement department staff count
        $staff->department->decrement('staff_count');
        
        $staff->delete();
        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully');
    }
}