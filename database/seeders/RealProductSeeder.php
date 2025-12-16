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

        // Products data from spreadsheet
        $products = [
            // Mailer 10*20
            ['name' => 'ly Mailer 10*20 Hitam A', 'location' => 'A35', 'total' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Biru B', 'location' => 'A36', 'total' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Kuning B', 'location' => 'A36', 'total' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Orange B', 'location' => 'A36', 'total' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Pink B', 'location' => 'A35', 'total' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 ungu B', 'location' => 'A35', 'total' => 0, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Hijau C', 'location' => 'A35', 'total' => 2, 'price' => 500],
            ['name' => 'ly Mailer 10*20 Hitam D', 'location' => 'A35', 'total' => 2, 'price' => 500],
            
            // Mailer 15*25
            ['name' => 'ly Mailer 15*25 Hitam A', 'location' => 'B15', 'total' => 0, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Kuning B', 'location' => 'B15', 'total' => 0, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Pink A', 'location' => 'B15', 'total' => 1, 'price' => 600],
            ['name' => 'ly Mailer 15*25 Hijau D', 'location' => 'B15', 'total' => 4, 'price' => 600],
            
            // Mailer 16*20
            ['name' => 'ly Mailer 16*20 Putih A', 'location' => 'A35', 'total' => 0, 'price' => 550],
            ['name' => 'ly Mailer 16*20 Hijau A', 'location' => 'A35', 'total' => 0, 'price' => 550],
            ['name' => 'ly Mailer 16*20 Hijau C', 'location' => 'A35', 'total' => 1, 'price' => 550],
            ['name' => 'ly Mailer 16 20 Hitam D', 'location' => 'A35', 'total' => 3, 'price' => 550],
            
            // Mailer 17*30
            ['name' => 'ly Mailer 17*30 Biru A', 'location' => 'C15', 'total' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hijau A', 'location' => 'C15', 'total' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam A', 'location' => 'C15', 'total' => 10, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Kuning A', 'location' => 'C16', 'total' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Orange A', 'location' => 'C15', 'total' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Putih A', 'location' => 'C15', 'total' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Ungu A', 'location' => 'C15', 'total' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Ungu A', 'location' => 'C15', 'total' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Biru B', 'location' => 'C16', 'total' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hijau B', 'location' => 'C16', 'total' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Kuning B', 'location' => 'C16', 'total' => 1, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Orange B', 'location' => 'C16', 'total' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Pink B', 'location' => 'C16', 'total' => 0, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Silver B', 'location' => 'B35', 'total' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Ungu B', 'location' => 'C16', 'total' => 7, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam C', 'location' => 'B35', 'total' => 2, 'price' => 700],
            ['name' => 'ly Mailer 17*30 Hitam D', 'location' => 'B35', 'total' => 2, 'price' => 700],
            
            // Mailer 20*30
            ['name' => 'ly Mailer 20*30 Biru A', 'location' => 'C35', 'total' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hijau A', 'location' => 'C35', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hijau A', 'location' => 'B25', 'total' => 9, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Kuning A', 'location' => 'C35', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Orange A', 'location' => 'C35', 'total' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Putih A', 'location' => 'C35', 'total' => 5, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Pink A', 'location' => 'B15', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Ungu A', 'location' => 'B25', 'total' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Biru B', 'location' => 'C35', 'total' => 2, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hijau B', 'location' => 'C35', 'total' => 1, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam B', 'location' => 'C35', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Orange B', 'location' => 'B25', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Pink B', 'location' => 'B25', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Silver B', 'location' => 'C25', 'total' => 3, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Ungu B', 'location' => 'C25', 'total' => 0, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam C', 'location' => 'C25', 'total' => 3, 'price' => 800],
            ['name' => 'ly Mailer 20*30 Hitam D', 'location' => 'Bottom', 'total' => 6, 'price' => 800],
            
            // Mailer 25*35
            ['name' => 'ly Mailer 25*35 Biru A', 'location' => 'B35', 'total' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hijau A', 'location' => 'B35', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam A', 'location' => 'B35', 'total' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Kuning A', 'location' => 'B25', 'total' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Orange A', 'location' => 'B25', 'total' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Pink A', 'location' => 'B25', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Putih A', 'location' => 'B35', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Ungu A', 'location' => 'B35', 'total' => 3, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Biru B', 'location' => 'B25', 'total' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hijau B', 'location' => 'B25', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Kuning B', 'location' => 'B35', 'total' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Orange B', 'location' => 'B25', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Pink B', 'location' => 'B35', 'total' => 2, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Putih B', 'location' => 'A35', 'total' => 1, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Silver B', 'location' => 'B35', 'total' => 6, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Ungu B', 'location' => 'B25', 'total' => 0, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam C', 'location' => 'C35', 'total' => 12, 'price' => 900],
            ['name' => 'ly Mailer 25*35 Hitam D', 'location' => 'B15', 'total' => 7, 'price' => 900],
            
            // Mailer 30*40
            ['name' => 'ly Mailer 30*40 Hitam A', 'location' => 'C25', 'total' => 7, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Ungu A', 'location' => 'C25', 'total' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Putih A', 'location' => 'C25', 'total' => 1, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Ungu A', 'location' => 'C25', 'total' => 0, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Silver B', 'location' => 'B16', 'total' => 0, 'price' => 1000],
            ['name' => 'ly Mailer 30*40 Hitam C', 'location' => 'C25', 'total' => 1, 'price' => 1000],
            
            // Mailer 32*40
            ['name' => 'ly Mailer 32*40 Biru A', 'location' => 'C45', 'total' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hijau A', 'location' => 'C45', 'total' => 4, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hitam A', 'location' => 'Bottom', 'total' => 30, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Kuning A', 'location' => 'C45', 'total' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Orange A', 'location' => 'C45', 'total' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Pink A', 'location' => 'C45', 'total' => 4, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Putih A', 'location' => 'C45', 'total' => 1, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Ungu A', 'location' => 'C45', 'total' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Silver B', 'location' => 'C45', 'total' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hitam C', 'location' => 'C45', 'total' => 0, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Biru B', 'location' => 'B45', 'total' => 2, 'price' => 1100],
            ['name' => 'ly Mailer 32*40 Hijau B', 'location' => 'B45', 'total' => 1, 'price' => 1100],
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
            if ($productData['total'] > 0) {
                $locationKey = $productData['location'];
                if (isset($locationModels[$locationKey])) {
                    Stock::create([
                        'product_id' => $product->id,
                        'location_id' => $locationModels[$locationKey]->id,
                        'quantity' => $productData['total']
                    ]);
                }
            }
        }

        $this->command->info('Real product data seeded successfully!');
    }
}
