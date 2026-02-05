<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
        // Doesn't guard models
        Model::unguard();
        // Prevents lazy loading (n +1) and prevents accessing eloquent model attributes that don't exist
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
    }
}
