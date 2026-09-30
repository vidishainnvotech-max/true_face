<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {

            // Primary Key
            $table->id();

            // Tenant / Company Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Organization Relationships
            $table->unsignedBigInteger('business_unit_id')
                ->nullable();

            $table->unsignedBigInteger('plant_id')
                ->nullable();

            $table->unsignedBigInteger('branch_id')
                ->nullable();

            $table->unsignedBigInteger('parent_location_id')
                ->nullable();

            // Public Identification
            $table->char('public_id', 26)
                ->unique();

            $table->string('code', 32)
                ->unique();

            $table->string('name', 160);

            // Location Type
            $table->string('location_kind', 32)
                ->default('site');

            // Address
            $table->string('address_line_1', 190)
                ->nullable();

            $table->string('address_line_2', 190)
                ->nullable();

            $table->string('city', 100)
                ->nullable();

            $table->string('state_code', 32)
                ->nullable();

            $table->char('country_code', 2)
                ->default('IN');

            $table->string('postal_code', 20)
                ->nullable();

            // Geographic Coordinates
            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->unsignedInteger('default_geofence_radius_m')
                ->nullable();

            // Timezone
            $table->string('timezone', 64)
                ->default('UTC');

            // Feature Flags
            $table->boolean('attendance_enabled')
                ->default(true);

            $table->boolean('access_control_enabled')
                ->default(false);

            // Lifecycle
            $table->string('status', 24)
                ->default('active')
                ->index();

            $table->date('effective_from')
                ->nullable();

            $table->date('effective_to')
                ->nullable();

            // Settings
            $table->json('settings')
                ->nullable();

            // Timestamps
            $table->timestamps(6);

            // Soft Delete
            $table->softDeletes('deleted_at', 6);

            /*
             * Indexes
             */

            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('plant_id');
            $table->index('branch_id');
            $table->index('parent_location_id');

            /*
             * Composite key required for
             * tenant + company scoped foreign keys
             */
            $table->unique(
                ['tenant_id', 'company_id', 'id'],
                'loc_scope_unique'
            );

            /*
             * Tenant
             */
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            /*
             * Company
             */
            $table->foreign(
                ['tenant_id', 'company_id'],
                'loc_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            /*
             * Business Unit
             */
            $table->foreign(
                ['tenant_id', 'company_id', 'business_unit_id'],
                'loc_business_unit_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('business_units')
                ->restrictOnDelete();

            /*
             * Plant
             */
            $table->foreign(
                ['tenant_id', 'company_id', 'plant_id'],
                'loc_plant_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('plants')
                ->restrictOnDelete();

            /*
             * Branch
             */
            $table->foreign(
                ['tenant_id', 'company_id', 'branch_id'],
                'loc_branch_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('branches')
                ->restrictOnDelete();

            /*
             * Parent Location
             */
            $table->foreign(
                ['tenant_id', 'company_id', 'parent_location_id'],
                'loc_parent_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('locations')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};