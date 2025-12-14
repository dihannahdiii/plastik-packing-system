<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@plastik.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Warehouse Staff User
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@plastik.com',
            'password' => bcrypt('password'),
            'role' => 'gudang',
        ]);

        // Create Locations
        $locations = [
            ['name' => 'Rak A1', 'description' => 'Rak bagian depan sebelah kiri'],
            ['name' => 'Rak A2', 'description' => 'Rak bagian depan sebelah kanan'],
            ['name' => 'Rak B1', 'description' => 'Rak bagian tengah sebelah kiri'],
            ['name' => 'Rak B2', 'description' => 'Rak bagian tengah sebelah kanan'],
            ['name' => 'Rak C1', 'description' => 'Rak bagian belakang sebelah kiri'],
            ['name' => 'Rak C2', 'description' => 'Rak bagian belakang sebelah kanan'],
        ];

        foreach ($locations as $location) {
            \App\Models\Location::create($location);
        }

        // Create Products
        $products = [
            ['name' => 'Plastik HD 10x15 cm', 'description' => 'Plastik ukuran kecil untuk kemasan', 'price' => 500],
            ['name' => 'Plastik HD 15x20 cm', 'description' => 'Plastik ukuran sedang', 'price' => 750],
            ['name' => 'Plastik HD 20x30 cm', 'description' => 'Plastik ukuran besar', 'price' => 1000],
            ['name' => 'Plastik PP 10x15 cm', 'description' => 'Plastik polipropilen kecil', 'price' => 600],
            ['name' => 'Plastik PP 15x20 cm', 'description' => 'Plastik polipropilen sedang', 'price' => 850],
            ['name' => 'Plastik PP 20x30 cm', 'description' => 'Plastik polipropilen besar', 'price' => 1200],
            ['name' => 'Bubble Wrap Roll', 'description' => 'Roll bubble wrap 100 meter', 'price' => 50000],
            ['name' => 'Lakban Bening', 'description' => 'Lakban bening 2 inch', 'price' => 5000],
            ['name' => 'Lakban Coklat', 'description' => 'Lakban coklat 2 inch', 'price' => 5000],
            ['name' => 'Kardus Kecil', 'description' => 'Kardus ukuran 20x20x20 cm', 'price' => 3000],
        ];

        foreach ($products as $productData) {
            $product = \App\Models\Product::create($productData);
            
            // Add stock to random locations
            $randomLocations = \App\Models\Location::inRandomOrder()->take(rand(1, 3))->get();
            foreach ($randomLocations as $location) {
                \App\Models\Stock::create([
                    'product_id' => $product->id,
                    'location_id' => $location->id,
                    'quantity' => rand(50, 500),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
    }
}
