<?php

namespace Database\Seeders;

use App\Models\InventoryRequest;
use App\Models\DeptUser;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    public function run()
    {
        // Get the first department user from our seeded data
        $deptUser = DeptUser::first();
        
        // Get the first item from our seeded data
        $item = Item::first();

        if (!$deptUser || !$item) {
            throw new \Exception('Please ensure DeptUser and Item seeders have been run first');
        }

        $requests = [
            [
                'item_id' => $item->id,
                'dept_user_id' => $deptUser->id,
                'quantity_requested' => 5,
                'requested_date' => '2024-09-18',
                'delivery_date' => '2024-10-03',
                'approved_date' => '2024-09-20',
                'status' => 'approved'
            ],
            [
                'item_id' => $item->id,
                'dept_user_id' => $deptUser->id,
                'quantity_requested' => 3,
                'requested_date' => Carbon::now(),
                'delivery_date' => Carbon::now()->addDays(15),
                'status' => 'pending'
            ]
        ];

        foreach ($requests as $request) {
            InventoryRequest::create($request);
        }
    }
}
