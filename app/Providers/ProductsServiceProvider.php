<?php

namespace App\Providers;

use App\Clients\KinfirmClient;
use App\Contracts\ProductsClientContract;
use Illuminate\Support\ServiceProvider;

class ProductsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductsClientContract::class, function ($app) {
            return new KinfirmClient($app->make('config')->get('kinfirm'));
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [ProductsClientContract::class];
    }
}
