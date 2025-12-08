<?php

namespace App\Modules\OJT\Infrastructure\Providers;

use App\Modules\OJT\Domain\Repositories\OjtLogRepositoryInterface;
use App\Modules\OJT\Infrastructure\Repositories\EloquentOjtLogRepository;
use Illuminate\Support\ServiceProvider;

/**
 * OJT Module Service Provider
 * Registers repository bindings
 */
class OjtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register routes, migrations, etc. here if needed
    }
}
