<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrement direct des middlewares de Spatie sans singleton
        app('router')->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);
        app('router')->aliasMiddleware('permission', \Spatie\Permission\Middlewares\PermissionMiddleware::class);
        app('router')->aliasMiddleware('role_or_permission', \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class);
    }
}
