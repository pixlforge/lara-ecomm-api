<?php

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::where('slug', 'supplements')->first();

        $category->products()->saveMany([
            factory(Product::class)->make([
                'name' => 'Caffein'
            ]),

            factory(Product::class)->make([
                'name' => 'Whey Protein'
            ]),

            factory(Product::class)->make([
                'name' => 'Pre workout'
            ]),

            factory(Product::class)->make([
                'name' => 'BCAA'
            ]),

            factory(Product::class)->make([
                'name' => 'Multivitamins'
            ]),

            factory(Product::class)->make([
                'name' => 'Creatine'
            ]),
        ]);
    }
}
