<?php

namespace App\Providers;

use App\Contracts\IUrlHash;
use App\Contracts\IUrlShortener;
use App\Services\UrlMD5BaseHash;
use App\Services\UrlShortener;
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
        $this->app->bind(IUrlShortener::class, UrlShortener::class);
        $this->app->bind(IUrlHash::class, UrlMD5BaseHash::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
