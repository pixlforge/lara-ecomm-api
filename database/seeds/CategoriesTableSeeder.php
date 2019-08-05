<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class)->create([
            'name' => 'Men'
        ]);
        
        factory(Category::class)->create([
            'name' => 'Women'
        ]);

        factory(Category::class)->create([
            'name' => 'Kids'
        ]);

        factory(Category::class)->create([
            'name' => 'Supplements'
        ]);
    }
}
