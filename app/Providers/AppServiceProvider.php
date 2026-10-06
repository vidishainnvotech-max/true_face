<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Tenant
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Eloquent\TenantRepository;

// Company
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Eloquent\CompanyRepository;

// Legal Entity
use App\Repositories\Contracts\LegalEntityRepositoryInterface;
use App\Repositories\Eloquent\LegalEntityRepository;

// Role
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Eloquent\RoleRepository;

// User
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;

// Permission
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Eloquent\PermissionRepository;

use App\Repositories\Contracts\RolePermissionRepositoryInterface;
use App\Repositories\Eloquent\RolePermissionRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Tenant Repository
        $this->app->bind(
            TenantRepositoryInterface::class,
            TenantRepository::class
        );

        // Company Repository
        $this->app->bind(
            CompanyRepositoryInterface::class,
            CompanyRepository::class
        );

        // Legal Entity Repository
        $this->app->bind(
            LegalEntityRepositoryInterface::class,
            LegalEntityRepository::class
        );

        // Role Repository
        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        // User Repository
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Permission Repository
        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );


        // Role Permission Repository
        $this->app->bind(
            RolePermissionRepositoryInterface::class,
            RolePermissionRepository::class
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
