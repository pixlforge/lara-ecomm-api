<?php

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\ProductVariation;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variations = ProductVariation::all();

        foreach ($variations as $variation) {
            factory(Stock::class)->create([
                'product_variation_id' => $variation->id
            ]);
        }
    }
}
