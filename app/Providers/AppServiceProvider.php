<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Observers\UserObserver;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerObservers();
    }

    /**
     * The models to observe.
     *
     * @return void
     */
    protected function registerObservers()
    {
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);
    }
}
