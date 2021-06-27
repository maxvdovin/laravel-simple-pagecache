<?php

namespace MXJ\PageCache;

use Illuminate\Support\ServiceProvider;

class PageCacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pagecache.php', 'pagecache');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/pagecache.php' => config_path('pagecache.php'),
        ]);
    }
}
