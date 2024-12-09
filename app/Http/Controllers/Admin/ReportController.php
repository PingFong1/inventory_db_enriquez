<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Department;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate()
    {
        // Basic implementation - you can enhance this based on your needs
        $items = Item::all();
        $departments = Department::with('items')->get();

        return view('admin.reports.generate', compact('items', 'departments'));
    }
} 