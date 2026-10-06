<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\TenantUserController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\LegalEntityController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RolePermissionController;

Route::prefix('v1')->group(function () {

    // =====================================================
    // Public API
    // =====================================================

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    // =====================================================
    // Protected APIs
    // =====================================================

    Route::middleware('auth:sanctum')->group(function () {

        // =================================================
        // Tenant CRUD
        // =================================================

        Route::apiResource(
            'tenants',
            TenantController::class
        );

        // =================================================
        // Tenant User Create
        // =================================================

        Route::post(
            'tenants/{tenant}/users',
            [TenantUserController::class, 'store']
        )->name('tenants.users.store');

        // =================================================
        // User APIs
        // =================================================

        // List users - requires users.view permission
        Route::get(
            'users',
            [UserController::class, 'index']
        )->middleware('permission:users.view');

        // Create user
        Route::post(
            'users',
            [UserController::class, 'store']
        );

        // Show user
        Route::get(
            'users/{user}',
            [UserController::class, 'show']
        );

        // Update user
        Route::put(
            'users/{user}',
            [UserController::class, 'update']
        );

        Route::patch(
            'users/{user}',
            [UserController::class, 'update']
        );

        // Delete user
        Route::delete(
            'users/{user}',
            [UserController::class, 'destroy']
        );

        // =================================================
        // Company CRUD
        // =================================================

        Route::apiResource(
            'companies',
            CompanyController::class
        );

        // =================================================
        // Legal Entity CRUD
        // =================================================

        Route::apiResource(
            'legal-entities',
            LegalEntityController::class
        );

        // =================================================
        // Role CRUD
        // =================================================

        Route::apiResource(
            'roles',
            RoleController::class
        );

        // =================================================
        // Permission CRUD
        // =================================================

        Route::apiResource(
            'permissions',
            PermissionController::class
        );

        // =================================================
        // Role Permission
        // =================================================

        // Assign permission to role
        Route::post(
            'role-permissions',
            [RolePermissionController::class, 'store']
        )->name('role-permissions.store');

        // Get permissions assigned to a role
        Route::get(
            'roles/{role}/permissions',
            [RolePermissionController::class, 'index']
        )->name('roles.permissions.index');

        // Remove permission from role
        Route::delete(
            'roles/{role}/permissions/{permission}',
            [RolePermissionController::class, 'destroy']
        )->name('roles.permissions.destroy');
    });
});
    