<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);

        if (config('app.env') === 'local') {
            $this->call(ProductsTableSeeder::class);
            $this->call(ProductVariationTypesTableSeeder::class);
            $this->call(ProductVariationsTableSeeder::class);
            $this->call(StocksTableSeeder::class);
        }
    }
}
