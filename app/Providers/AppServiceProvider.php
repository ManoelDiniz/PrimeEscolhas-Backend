<?php

namespace App\Providers;

use App\Models\BlogPosts;
use App\Models\Products;
use App\Observers\BlogPostObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar observers para indexação automática
        BlogPosts::observe(BlogPostObserver::class);
        Products::observe(ProductObserver::class);
    }
}
