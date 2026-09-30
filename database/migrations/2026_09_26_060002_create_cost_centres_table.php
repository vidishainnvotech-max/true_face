<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_centres', function (Blueprint $table) {

            $table->id();

            // Ownership / Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Optional organization context
            $table->unsignedBigInteger('legal_entity_id')->nullable();
            $table->unsignedBigInteger('business_unit_id')->nullable();
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            // Public identity
            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 160);

            // External integration
            $table->string('external_payroll_code', 64)->nullable();

            // Lifecycle
            $table->string('status', 24)
                ->default('active');

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            // Extension settings
            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('department_id');
            $table->index('status');

            // Required composite key for scoped FK references
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'id'
                ],
                'cost_centre_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'cost_centre_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Legal Entity scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'legal_entity_id'
                ],
                'cost_centre_legal_entity_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('legal_entities')
                ->restrictOnDelete();

            // Business Unit scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'business_unit_id'
                ],
                'cost_centre_business_unit_fk'
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
                'cost_centre_plant_fk'
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
                'cost_centre_branch_fk'
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
                'cost_centre_location_fk'
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

            // Department scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'department_id'
                ],
                'cost_centre_department_fk'
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
        Schema::dropIfExists('cost_centres');
    }
};