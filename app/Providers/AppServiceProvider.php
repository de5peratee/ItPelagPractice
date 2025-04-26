<?php

namespace App\Providers;

use App\Repositories\BucketRepository;
use App\Repositories\BucketRepositoryInterface;
use App\Services\LeakyBucketService;
use App\Services\StorageLoggerService;
use App\Strategies\LeakStrategyInterface;
use App\Strategies\TimeBasedLeakStrategy;
use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Facades\Octane;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(BucketRepositoryInterface::class, function ($app) {
            return new BucketRepository(
                config('leaky_bucket'),
                $app->make(StorageLoggerService::class)
            );
        });

        $this->app->bind(LeakStrategyInterface::class, TimeBasedLeakStrategy::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
