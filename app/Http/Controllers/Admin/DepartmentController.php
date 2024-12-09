<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:unique:departments'
        ]);

        $department = Department::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'department' => $department
            ]);
        }

        return redirect()->back()->with('success', 'Department created successfully!');
    }

    public function checkHead(Department $department)
    {
        $hasHead = Staff::where('department_id', $department->id)
            ->where('role', 'department_head')
            ->exists();

        return response()->json(['hasHead' => $hasHead]);
    }
}