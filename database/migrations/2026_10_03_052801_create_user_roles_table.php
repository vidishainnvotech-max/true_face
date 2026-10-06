<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {

            $table->id();

            // Tenant
            $table->unsignedBigInteger('tenant_id');

            // User
            $table->unsignedBigInteger('user_id');

            // Role
            $table->unsignedBigInteger('role_id');

            // Audit timestamps
            $table->timestamps(6);

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('tenant_id', 'fk_user_roles_tenant')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign('user_id', 'fk_user_roles_user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('role_id', 'fk_user_roles_role')
                ->references('id')
                ->on('roles')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Assignment
            |--------------------------------------------------------------------------
            */

            $table->unique(
                ['user_id', 'role_id'],
                'uq_user_roles_user_role'
            );

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['tenant_id', 'user_id'],
                'ix_user_roles_tenant_user'
            );

            $table->index(
                ['tenant_id', 'role_id'],
                'ix_user_roles_tenant_role'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};