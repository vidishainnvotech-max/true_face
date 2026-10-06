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
        Schema::create('role_permissions', function (Blueprint $table) {

            $table->id();

            // Tenant context
            $table->unsignedBigInteger('tenant_id');

            // Role
            $table->unsignedBigInteger('role_id');

            // Permission
            $table->unsignedBigInteger('permission_id');

            $table->timestamps(6);

            // =====================================================
            // UNIQUE CONSTRAINT
            // =====================================================

            // Same permission cannot be assigned
            // to the same role twice.
            $table->unique(
                ['role_id', 'permission_id'],
                'uq_role_permissions_role_permission'
            );

            // =====================================================
            // INDEX
            // =====================================================

            $table->index(
                ['tenant_id', 'role_id'],
                'ix_role_permissions_tenant_role'
            );

            // =====================================================
            // FOREIGN KEYS
            // =====================================================

            $table->foreign(
                'tenant_id',
                'fk_role_permissions_tenant'
            )
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'role_id',
                'fk_role_permissions_role'
            )
                ->references('id')
                ->on('roles')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'permission_id',
                'fk_role_permissions_permission'
            )
                ->references('id')
                ->on('permissions')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};