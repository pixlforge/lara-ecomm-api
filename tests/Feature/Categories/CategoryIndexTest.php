<?php

namespace Tests\Feature\Categories;

use Tests\TestCase;
use App\Models\Category;

class CategoryIndexTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection_of_categories()
    {
        $categories = factory(Category::class, 2)->create();

        $response = $this->getJson(route('categories.index'));

        $categories->each(function ($category) use ($response) {
            $response->assertJsonFragment([
                'slug' => $category->slug
            ]);
        });
    }

    /** @test */
    public function it_returns_only_parent_categories()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->create()
        );

        $response = $this->getJson(route('categories.index'));

        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_returns_categories_ordered_by_their_given_order()
    {
        $shoes = factory(Category::class)->create([
            'name' => 'Shoes',
            'order' => 2
        ]);

        $hats = factory(Category::class)->create([
            'name' => 'Hats',
            'order' => 1
        ]);

        $response = $this->getJson(route('categories.index'));
        
        $response->assertSeeInOrder([
            $hats->slug, $shoes->slug
        ]);
    }
}
