<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {

            $table->id();

            // Ownership / Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Optional organization / physical context
            $table->unsignedBigInteger('business_unit_id')->nullable();
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();

            // Department hierarchy
            $table->unsignedBigInteger('parent_department_id')->nullable();

            // Public identity
            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 160);

            // Department details
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('sort_order')
                ->default(0);

            $table->string('status', 24)
                ->default('active');

            // Effective period
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            // Extension settings
            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('location_id');
            $table->index('parent_department_id');
            $table->index('status');

            // Required composite key for scoped FK references
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'id'
                ],
                'department_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'department_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Business Unit scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'business_unit_id'
                ],
                'department_business_unit_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('business_units')
                ->restrictOnDelete();

            // Plant scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'plant_id'
                ],
                'department_plant_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('plants')
                ->restrictOnDelete();

            // Branch scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'branch_id'
                ],
                'department_branch_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('branches')
                ->restrictOnDelete();

            // Location scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id'
                ],
                'department_location_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('locations')
                ->restrictOnDelete();

            // Parent Department FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'parent_department_id'
                ],
                'department_parent_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('departments')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};