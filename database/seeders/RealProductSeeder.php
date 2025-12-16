<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class RealProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (SQLite compatible) - but keep users!
        DB::statement('PRAGMA foreign_keys = OFF;');
        Stock::truncate();
        Product::truncate();
        Location::truncate();
        DB::statement('PRAGMA foreign_keys = ON;');
        
        // Recreate users if they don't exist
        if (\App\Models\User::count() === 0) {
            \App\Models\User::create([
                'name' => 'Admin',
                'email' => 'admin@plastik.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);

            \App\Models\User::create([
                'name' => 'Staff Gudang',
                'email' => 'gudang@plastik.com',
                'password' => bcrypt('password'),
                'role' => 'gudang',
            ]);
        }

        // Create Locations based on the spreadsheet
        $locations = [
            'A35', 'B15', 'B35', 'B25', 'C15', 'C35', 'C25', 
            'Bottom', 'C45', 'B45', 'C25', 'B16'
        ];

        $locationModels = [];
        foreach (array_unique($locations) as $loc) {
            $locationModels[$loc] = Location::create([
                'name' => $loc,
                'description' => 'Lokasi Rak ' . $loc
            ]);
        }

        // Products data from spreadsheet with real stock quantities
        $products = [
            // Mailer 10*20 (Ruangan 1)
            ['name' => 'ly Mailer 10*20 Hitam A', 'location' => 'A35', 'stock' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Biru B', 'location' => 'A35', 'stock' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Kuning B', 'location' => 'A35', 'stock' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Orange B', 'location' => 'A35', 'stock' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Pink B', 'location' => 'A35', 'stock' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 ungu A', 'location' => 'A35', 'stock' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Hitam C', 'location' => 'A35', 'stock' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Hijau D', 'location' => 'A35', 'stock' => 2, 'price' => 500],
            
            // Mailer 15*25
            ['name' => 'ly Mailer 15*25 Hitam A', 'location' => 'B15', 'stock' => 0, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Kuning B', 'location' => 'B15', 'stock' => 0, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Pink A', 'location' => 'B15', 'stock' => 1, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Hitam D', 'location' => 'B15', 'stock' => 4, 'price' => 600],
            
            // Mailer 16*20
            ['name' => 'ly Mailer 16*20 Putih A', 'location' => 'A35', 'stock' => 0, 'price' => 550],
            ['name' => 'ly Mailer 16*20 Hitam A', 'location' => 'A35', 'stock' => 0, 'price' => 550],
            ['name' => 'ly Mailer 16*20 Hitam C', 'location' => 'A35', 'stock' => 1, 'price' => 550],
            ['name' => 'ly Mailer 16 20 Hitam D', 'location' => 'A35', 'stock' => 3, 'price' => 550],
            
            // Mailer 17*30
            ['name' => 'ly Mailer 17*30 Biru A', 'location' => 'C15', 'stock' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hijau A', 'location' => 'C15', 'stock' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam B', 'location' => 'C15', 'stock' => 10, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Kuning A', 'location' => 'C15', 'stock' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Orange A', 'location' => 'C15', 'stock' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Putih A', 'location' => 'C15', 'stock' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Orqal A', 'location' => 'C15', 'stock' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Ungu A', 'location' => 'C15', 'stock' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Biru B', 'location' => 'C15', 'stock' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hijau B', 'location' => 'C15', 'stock' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Kuning B', 'location' => 'C15', 'stock' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Orange B', 'location' => 'C15', 'stock' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Pink B', 'location' => 'C15', 'stock' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Silver B', 'location' => 'B35', 'stock' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Ungu B', 'location' => 'C15', 'stock' => 7, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam C', 'location' => 'C15', 'stock' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam D', 'location' => 'B35', 'stock' => 2, 'price' => 700],
            
            // Mailer 20*30
            ['name' => 'ly Mailer 20*30 Biru A', 'location' => 'C35', 'stock' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hijau A', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam A', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Kuning A', 'location' => 'C35', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Orange A', 'location' => 'C35', 'stock' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Putih A', 'location' => 'B15', 'stock' => 5, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Pink A', 'location' => 'B15', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Ungu A', 'location' => 'B25', 'stock' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Biru B', 'location' => 'C35', 'stock' => 2, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hijau B', 'location' => 'C35', 'stock' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam B', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Kuning B', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Orange B', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Pink B', 'location' => 'B25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Silver B', 'location' => 'C25', 'stock' => 3, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Ungu B', 'location' => 'C25', 'stock' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam C', 'location' => 'C25', 'stock' => 3, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam D', 'location' => 'Bottom', 'stock' => 6, 'price' => 800],
            
            // Mailer 25*35
            ['name' => 'ly Mailer 25*35 Biru A', 'location' => 'B25', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hijau A', 'location' => 'B25', 'stock' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam A', 'location' => 'B35', 'stock' => 15, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Kuning A', 'location' => 'B25', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Orange A', 'location' => 'B25', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Pink A', 'location' => 'B25', 'stock' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Putih A', 'location' => 'B35', 'stock' => 3, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Ungu A', 'location' => 'B25', 'stock' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Biru B', 'location' => 'B25', 'stock' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hijau B', 'location' => 'B25', 'stock' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Kuning B', 'location' => 'B35', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Orange B', 'location' => 'B35', 'stock' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Pink B', 'location' => 'B35', 'stock' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Orqal B', 'location' => 'A35', 'stock' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Silver B', 'location' => 'B35', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Ungu B', 'location' => 'B25', 'stock' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam C', 'location' => 'C35', 'stock' => 12, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam D', 'location' => 'B15', 'stock' => 7, 'price' => 900],
            
            // Mailer 25*40
            ['name' => 'ly Mailer 25*40 Hitam A', 'location' => 'C25', 'stock' => 7, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Hijau A', 'location' => 'C25', 'stock' => 1, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Putih A', 'location' => 'C25', 'stock' => 1, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Ungu A', 'location' => 'C25', 'stock' => 0, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Silver B', 'location' => 'B15', 'stock' => 0, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Hitam C', 'location' => 'C25', 'stock' => 1, 'price' => 950],
            ['name' => 'ly Mailer 25*40 Hitam D', 'location' => 'C25', 'stock' => 0, 'price' => 950],
            
            // Mailer 30*40
            ['name' => 'ly Mailer 30*40 Biru A', 'location' => 'C45', 'stock' => 0, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Hijau A', 'location' => 'C45', 'stock' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Hitam A', 'location' => 'Bottom', 'stock' => 30, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Kuning A', 'location' => 'Bottom', 'stock' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Orange A', 'location' => 'C45', 'stock' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Pink A', 'location' => 'C45', 'stock' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Putih A', 'location' => 'C45', 'stock' => 4, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Silver A', 'location' => 'C45', 'stock' => 10, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Ungu A', 'location' => 'C45', 'stock' => 0, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Biru B', 'location' => 'C45', 'stock' => 0, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Hijau B', 'location' => 'B45', 'stock' => 2, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Ungu B', 'location' => 'B45', 'stock' => 1, 'price' => 1000],
            
            ['name' => 'ly Mailer 30*40 Hitam C', 'location' => 'B45', 'stock' => 4, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Hitam D', 'location' => 'B45', 'stock' => 10, 'price' => 1000],
            
            // Mailer 32*40
            ['name' => 'ly Mailer 32*40 Hitam A', 'location' => 'B45', 'stock' => 5, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hijau A', 'location' => 'B45', 'stock' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Putih A', 'location' => 'B16', 'stock' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Silver A', 'location' => 'B45', 'stock' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Ungu A', 'location' => 'B16', 'stock' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hijau B', 'location' => 'B45', 'stock' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hitam C', 'location' => 'B45', 'stock' => 3, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hitam D', 'location' => 'B45', 'stock' => 1, 'price' => 1100],
        ];

        // Insert products and their stock
        foreach ($products as $productData) {
            // Create product
            $product = Product::create([
                'name' => $productData['name'],
                'description' => 'Plastik mailer ' . $productData['name'],
                'price' => $productData['price']
            ]);

            // Create stock for this product at specified location
            $locationKey = $productData['location'];
            if (isset($locationModels[$locationKey])) {
                Stock::create([
                    'product_id' => $product->id,
                    'location_id' => $locationModels[$locationKey]->id,
                    'quantity' => $productData['stock']
                ]);
            }
        }

        $this->command->info('Real product data seeded successfully!');
    }
}
