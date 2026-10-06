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
        Schema::create('permissions', function (Blueprint $table) {

            $table->id();

            // Public identifier
            $table->char('public_id', 26)
                ->unique();

            // Tenant ownership
            // NULL = system/platform permission
            $table->unsignedBigInteger('tenant_id')
                ->nullable();

            // Permission identity
            $table->string('code', 120);

            $table->string('name', 160);

            $table->string('description', 500)
                ->nullable();

            // Module to which permission belongs
            // users, roles, companies, etc.
            $table->string('module', 80);

            // Action
            // view, create, update, delete, etc.
            $table->string('action', 40);

            // active / archived
            $table->string('status', 24)
                ->default('active')
                ->index();

            // Audit information
            $table->unsignedBigInteger('created_by_user_id')
                ->nullable();

            $table->unsignedBigInteger('updated_by_user_id')
                ->nullable();

            $table->timestamps(6);

            // Soft delete
            $table->softDeletes('deleted_at', 6);

            // =====================================================
            // UNIQUE CONSTRAINT
            // =====================================================

            $table->unique(
                ['tenant_id', 'code'],
                'uq_permissions_tenant_code'
            );

            // =====================================================
            // INDEXES
            // =====================================================

            $table->index(
                ['tenant_id', 'status'],
                'ix_permissions_tenant_status'
            );

            $table->index(
                ['module', 'action'],
                'ix_permissions_module_action'
            );

            // =====================================================
            // FOREIGN KEYS
            // =====================================================

            $table->foreign(
                'tenant_id',
                'fk_permissions_tenant'
            )
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'created_by_user_id',
                'fk_permissions_created_by'
            )
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'updated_by_user_id',
                'fk_permissions_updated_by'
            )
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};