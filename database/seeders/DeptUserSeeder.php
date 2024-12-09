<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeptUserSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['dept_name' => '', 'user_name' => ' User', 'email' => '@example.com'],
            ['dept_name' => 'CCJ', 'user_name' => 'CCJ User', 'email' => 'ccj@example.com'],
            ['dept_name' => 'MED', 'user_name' => 'MED User', 'email' => 'med@example.com'],
            ['dept_name' => 'ENG', 'user_name' => 'ENG User', 'email' => 'eng@example.com'],
            ['dept_name' => 'ARC', 'user_name' => 'ARC User', 'email' => 'arc@example.com'],
            ['dept_name' => 'MTH', 'user_name' => 'MTH User', 'email' => 'mth@example.com'],
            ['dept_name' => 'BUS', 'user_name' => 'BUS User', 'email' => 'bus@example.com'],
            ['dept_name' => 'IT', 'user_name' => 'IT User', 'email' => 'it@example.com'],
        ];

        foreach ($departments as $dept) {
            DB::table('dept_users')->insert([
                'dept_name' => $dept['dept_name'],
                'user_name' => $dept['user_name'],
                'email' => $dept['email'],
                'password' => Hash::make('password123'),
                'phone' => '123-456-7890',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
