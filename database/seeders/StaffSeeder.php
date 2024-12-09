<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\Department;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $department = Department::first();

        Staff::create([
            'name' => 'Department Manager',
            'email' => 'manager@department.com',
            'password' => Hash::make('password123'),
            'employee_id' => 'EMP001',
            'department_id' => $department->id,
            'role' => 'department_head',
            'phone' => '1234567890',
            'status' => 'active'
        ]);
    }
}