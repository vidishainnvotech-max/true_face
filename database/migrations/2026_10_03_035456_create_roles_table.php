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
        Schema::create('roles', function (Blueprint $table) {

            $table->id();

            // =====================================================
            // PUBLIC IDENTIFIER
            // =====================================================

            $table->char('public_id', 26)
                ->unique();

            // =====================================================
            // TENANT OWNERSHIP
            // NULL = SYSTEM ROLE
            // =====================================================

            $table->unsignedBigInteger('tenant_id')
                ->nullable();

            // Normalized scope helper
            $table->unsignedBigInteger('tenant_scope_id');

            // =====================================================
            // ROLE IDENTITY
            // =====================================================

            $table->string('code', 96);

            $table->string('name', 160);

            $table->string('description', 500)
                ->nullable();

            // system | tenant | company | location | department
            $table->string('scope_level', 24)
                ->default('tenant')
                ->index();

            // Platform-provided role
            $table->boolean('is_system')
                ->default(false)
                ->index();

            // Can new users be assigned this role?
            $table->boolean('is_assignable')
                ->default(true);

            // Lower value = stronger
            $table->unsignedSmallInteger('priority')
                ->default(100);

            // active / archived etc.
            $table->string('status', 24)
                ->default('active')
                ->index();

            // =====================================================
            // AUDIT INFORMATION
            // =====================================================

            $table->unsignedBigInteger('created_by_user_id')
                ->nullable();

            $table->unsignedBigInteger('updated_by_user_id')
                ->nullable();

            $table->timestamps(6);

            // =====================================================
            // SOFT DELETE
            // =====================================================

            $table->softDeletes('deleted_at', 6);

            // =====================================================
            // UNIQUE CONSTRAINTS
            // =====================================================

            // Role code unique within tenant/system scope
            $table->unique(
                ['tenant_scope_id', 'code'],
                'uq_roles_scope_code'
            );

            // Required for composite scope validation
            $table->unique(
                ['tenant_scope_id', 'id'],
                'uq_roles_scope_id'
            );

            // =====================================================
            // INDEX
            // =====================================================

            $table->index(
                ['tenant_id', 'status', 'deleted_at'],
                'ix_roles_tenant_status'
            );

            // =====================================================
            // FOREIGN KEYS
            // =====================================================

            $table->foreign('tenant_id', 'fk_roles_tenant')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'created_by_user_id',
                'fk_roles_created_by'
            )
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign(
                'updated_by_user_id',
                'fk_roles_updated_by'
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
        Schema::dropIfExists('roles');
    }
};