<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Chanel', 'logo' => '/assets/img/brands/chanel.svg'],
            ['name' => 'Dolce & Gabbana', 'logo' => '/assets/img/brands/dolce-and-gabbana.svg'],
            ['name' => 'Giorgio Armani', 'logo' => '/assets/img/brands/giorgio-armani.svg'],
            ['name' => 'Mancera', 'logo' => '/assets/img/brands/Mancera.svg'],
            ['name' => 'Versace', 'logo' => '/assets/img/brands/versace.svg'],
            ['name' => 'Yves Saint Laurent', 'logo' => '/assets/img/brands/yves-saint-laurent.svg'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
