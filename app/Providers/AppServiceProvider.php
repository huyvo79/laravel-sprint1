<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Import các Interface và Class đã tạo
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Auth\AuthService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\VariantRepositoryInterface::class,
            \App\Repositories\Eloquent\VariantRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}