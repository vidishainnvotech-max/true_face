<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gates', function (Blueprint $table) {

            $table->id();

            // Ownership / Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Physical location context
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('building_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();

            // Public identity
            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 120);

            // Gate configuration
            $table->string('gate_type', 32)
                ->default('pedestrian');

            $table->string('direction_mode', 24)
                ->default('bidirectional');

            $table->boolean('attendance_enabled')
                ->default(true);

            $table->boolean('access_control_enabled')
                ->default(false);

            $table->boolean('is_emergency_exit')
                ->default(false);

            // Lifecycle
            $table->string('status', 24)
                ->default('active');

            // Extension settings
            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('location_id');
            $table->index('building_id');
            $table->index('floor_id');
            $table->index('zone_id');
            $table->index('status');

            // Required composite key for scoped FK references
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'id'
                ],
                'gate_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'gate_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Location scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id'
                ],
                'gate_location_fk'
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

            // Building scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'building_id'
                ],
                'gate_building_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'location_id',
                        'id'
                    ]
                )
                ->on('buildings')
                ->restrictOnDelete();

            // Floor scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'building_id',
                    'floor_id'
                ],
                'gate_floor_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'location_id',
                        'building_id',
                        'id'
                    ]
                )
                ->on('floors')
                ->restrictOnDelete();

            // Zone scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'zone_id'
                ],
                'gate_zone_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'location_id',
                        'id'
                    ]
                )
                ->on('zones')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gates');
    }
};