<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\V1\ApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService();
        });
    }
}
