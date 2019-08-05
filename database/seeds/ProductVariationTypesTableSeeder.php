<?php

use Illuminate\Database\Seeder;
use App\Models\ProductVariationType;

class ProductVariationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ProductVariationType::class)->create([
            'name' => 'Tub'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'Bag'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'Strawberry'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'Chocolate'
        ]);

        factory(ProductVariationType::class)->create([
            'name' => 'Vanilla'
        ]);
    }
}
