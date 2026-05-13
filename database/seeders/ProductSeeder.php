<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'brand_slug' => 'chanel',
                'name' => 'Coco Mademoiselle EDP',
                'description' => 'Orientalni i sveži miris za savremenu ženu. Kombinacija pomorandže, pačulija i rose stvara eleganciju koja traje.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'CHL-CM-050', 'price' => 14500],
                    ['size_label' => '100ml', 'sku' => 'CHL-CM-100', 'price' => 19500],
                ],
                'image' => '/assets/img/pexels-suhashanjar-36779951.webp',
                'promotion' => ['size_index' => 1, 'sale_price' => 16500],
            ],
            [
                'brand_slug' => 'chanel',
                'name' => 'Chance Eau Tendre EDT',
                'description' => 'Nežan cvetni miris sa notama grejpfruta, kvefila i jasmina. Savršen za prolećne i letnje dane.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'CHL-CH-050', 'price' => 12800],
                    ['size_label' => '100ml', 'sku' => 'CHL-CH-100', 'price' => 17200],
                ],
                'image' => '/assets/img/pexels-perfect-lens-26571478.webp',
            ],
            [
                'brand_slug' => 'dolce-gabbana',
                'name' => 'Light Blue EDT',
                'description' => 'Osvježavajući mediteranski miris sa sicilijanskom limunom, zelenim jabukom i bambusom. Simbol letnjeg dana na obali.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'DG-LB-050', 'price' => 9200],
                    ['size_label' => '100ml', 'sku' => 'DG-LB-100', 'price' => 12800],
                ],
                'image' => '/assets/img/pexels-laurachouette-22589353.webp',
            ],
            [
                'brand_slug' => 'dolce-gabbana',
                'name' => 'The One EDP',
                'description' => 'Topao i začinski muški miris sa notama kardamona, đumbira i tamjana. Klasik za svaku priliku.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'DG-TO-050', 'price' => 10500],
                    ['size_label' => '100ml', 'sku' => 'DG-TO-100', 'price' => 14200],
                ],
                'image' => '/assets/img/pexels-rehman-yousaf-321165099-14490634.webp',
                'promotion' => ['size_index' => 0, 'sale_price' => 8500],
            ],
            [
                'brand_slug' => 'giorgio-armani',
                'name' => 'Acqua di Gio Profondo EDP',
                'description' => 'Morski i aromatični muški miris inspirisan dubinom okeana. Idealno za moderne avanturiste.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '75ml', 'sku' => 'GA-ADG-075', 'price' => 13200],
                    ['size_label' => '125ml', 'sku' => 'GA-ADG-125', 'price' => 16800],
                ],
                'image' => '/assets/img/pexels-diun-group-1148420145-21234956.webp',
            ],
            [
                'brand_slug' => 'giorgio-armani',
                'name' => 'Si EDP',
                'description' => 'Sofisticirani ženski miris sa crnom ribizlom, frezijom i vanilom. Izraz snage i elegancije.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'GA-SI-050', 'price' => 11800],
                    ['size_label' => '100ml', 'sku' => 'GA-SI-100', 'price' => 15800],
                ],
                'image' => '/assets/img/pexels-suhashanjar-36779951.webp',
            ],
            [
                'brand_slug' => 'mancera',
                'name' => 'Roses Vanille EDP',
                'description' => 'Gourmand cvetni miris sa turskom ruzom, vanilom i šećerom. Slatka i opojna kombinacija.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '60ml', 'sku' => 'MN-RV-060', 'price' => 13800],
                    ['size_label' => '120ml', 'sku' => 'MN-RV-120', 'price' => 18800],
                ],
                'image' => '/assets/img/pexels-perfect-lens-26571478.webp',
            ],
            [
                'brand_slug' => 'mancera',
                'name' => 'Cedrat Boise EDP',
                'description' => 'Voćno-drveti miris sa citrusom, cedrom i kožom. Jedinstvena i dugotrajna kompozicija.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '60ml', 'sku' => 'MN-CB-060', 'price' => 14200],
                    ['size_label' => '120ml', 'sku' => 'MN-CB-120', 'price' => 19200],
                ],
                'image' => '/assets/img/pexels-rehman-yousaf-321165099-14490634.webp',
            ],
            [
                'brand_slug' => 'versace',
                'name' => 'Eros EDT',
                'description' => 'Intenzivan muški miris sa mintom, zelenom jabukom i tonkom. Simbol strasti i snage.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'VS-ER-050', 'price' => 8200],
                    ['size_label' => '100ml', 'sku' => 'VS-ER-100', 'price' => 11200],
                ],
                'image' => '/assets/img/pexels-diun-group-1148420145-21234956.webp',
                'promotion' => ['size_index' => 1, 'sale_price' => 9200],
            ],
            [
                'brand_slug' => 'versace',
                'name' => 'Dylan Blue Pour Homme EDT',
                'description' => 'Aromatično-drveti miris sa bergamotom, biberom i pačulijem. Moderan i samouveren izbor.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'VS-DB-050', 'price' => 8800],
                    ['size_label' => '100ml', 'sku' => 'VS-DB-100', 'price' => 11800],
                ],
                'image' => '/assets/img/pexels-isidor-bobinec-94539949-9202888.webp',
            ],
            [
                'brand_slug' => 'yves-saint-laurent',
                'name' => 'Libre EDP',
                'description' => 'Svež cvetno-lavandin miris sa notama mandarine i jasmina. Miris slobode i samouverenosti.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'YSL-LB-050', 'price' => 13500],
                    ['size_label' => '90ml', 'sku' => 'YSL-LB-090', 'price' => 17200],
                ],
                'image' => '/assets/img/pexels-laurachouette-22589353.webp',
            ],
            [
                'brand_slug' => 'yves-saint-laurent',
                'name' => 'Black Opium EDP',
                'description' => 'Zavodljivi kafa-orijentalni miris sa notama kafe, ruže i vanile. Za one koji vole da se izdvajaju.',
                'type' => 'simple',
                'variants' => [
                    ['size_label' => '50ml', 'sku' => 'YSL-BO-050', 'price' => 14200],
                    ['size_label' => '90ml', 'sku' => 'YSL-BO-090', 'price' => 18200],
                ],
                'image' => '/assets/img/pexels-suhashanjar-36779954.webp',
                'promotion' => ['size_index' => 0, 'sale_price' => 11500],
            ],
        ];

        foreach ($products as $data) {
            $brand = Brand::where('slug', $data['brand_slug'])->first();

            if (! $brand) {
                continue;
            }

            $product = Product::create([
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'type' => $data['type'],
                'is_active' => true,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $data['image'],
                'alt_text' => $data['name'],
                'sort_order' => 0,
                'is_primary' => true,
            ]);

            foreach ($data['variants'] as $index => $variantData) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'size_label' => $variantData['size_label'],
                    'sku' => $variantData['sku'],
                    'is_active' => true,
                ]);

                Price::create([
                    'product_variant_id' => $variant->id,
                    'amount' => $variantData['price'],
                    'currency' => 'RSD',
                    'effective_date' => now(),
                ]);

                if (isset($data['promotion']) && $data['promotion']['size_index'] === $index) {
                    Promotion::create([
                        'product_variant_id' => $variant->id,
                        'sale_price' => $data['promotion']['sale_price'],
                        'starts_at' => now()->subDays(2),
                        'ends_at' => now()->addDays(10),
                    ]);
                }
            }
        }
    }
}
