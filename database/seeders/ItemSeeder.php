<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'Lab Coats',
                'description' => 'Laboratory protective wear',
                'current_quantity' => 50,
                'minimum_quantity' => 10,
                'maximum_quantity' => 100,
                'status' => 'available'
            ],
            [
                'name' => 'Microscopes',
                'description' => 'High-precision laboratory microscopes',
                'current_quantity' => 20,
                'minimum_quantity' => 5,
                'maximum_quantity' => 30,
                'status' => 'available'
            ],
            [
                'name' => 'Laptops',
                'description' => 'Standard laptops for general use',
                'current_quantity' => 25,
                'minimum_quantity' => 10,
                'maximum_quantity' => 50,
                'status' => 'available'
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
