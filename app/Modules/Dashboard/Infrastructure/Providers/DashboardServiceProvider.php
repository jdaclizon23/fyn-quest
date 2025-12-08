<?php

namespace App\Modules\Dashboard\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


/**
 * Dashboard Module Service Provider
 * Registers repository bindings
 */
class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            \App\Modules\Dashboard\Domain\Repositories\DashboardRepositoryInterface::class,
            \App\Modules\Dashboard\Infrastructure\Repositories\DashboardRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register routes, migrations, etc. here if needed
        // $this->loadRoutesFrom(__DIR__ . '/../../Presentation/Routes/dashboard.php');
    }

    private function mapApiRoutes(): void{
        Route::prefix('dashboard.php')
            ->middleware(['auth:web'])
            ->group(app_path('Modules/Dashboard/Presentation/Routes/dashboard.php.php'));
    }
}
