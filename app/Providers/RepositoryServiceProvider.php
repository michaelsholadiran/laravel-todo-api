<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\V1\Todo;
use App\Repositories\Eloquent\V1\TodoRepository;
use App\Repositories\Interfaces\V1\TodoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TodoRepositoryInterface::class, function ($app) {
            return new TodoRepository($app->make(Todo::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
