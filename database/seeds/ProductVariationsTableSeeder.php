<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class ProductVariationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Caffein pills
         */
        $product = Product::where('slug', 'caffein')->first();
        $productVariationTypeTub = ProductVariationType::where('name', 'Tub')->first();
        $productVariationTypeBag = ProductVariationType::where('name', 'Bag')->first();

        $product->variations()->saveMany([
            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeTub->id,
                'name' => '50 pc',
                'price' => $product->price->getAmount()
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeTub->id,
                'name' => '100 pc'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeTub->id,
                'name' => '200 pc'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeBag->id,
                'name' => '300 pc'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeBag->id,
                'name' => '500 pc'
            ]),
        ]);

        /**
         * Whey protein
         */
        $product = Product::where('slug', 'whey-protein')->first();
        $productVariationTypeStrawberry = ProductVariationType::where('name', 'Strawberry')->first();
        $productVariationTypeChocolate = ProductVariationType::where('name', 'Chocolate')->first();
        $productVariationTypeVanilla = ProductVariationType::where('name', 'Vanilla')->first();

        $product->variations()->saveMany([
            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeStrawberry->id,
                'name' => '500g',
                'price' => $product->price->getAmount()
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeStrawberry->id,
                'name' => '1kg'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeStrawberry->id,
                'name' => '2.5kg'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeChocolate->id,
                'name' => '500g',
                'price' => $product->price->getAmount()
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeChocolate->id,
                'name' => '1kg'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeChocolate->id,
                'name' => '2.5kg'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeVanilla->id,
                'name' => '500g',
                'price' => $product->price->getAmount()
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeVanilla->id,
                'name' => '1kg'
            ]),

            factory(ProductVariation::class)->make([
                'product_variation_type_id' => $productVariationTypeVanilla->id,
                'name' => '2.5kg'
            ]),
        ]);
    }
}
