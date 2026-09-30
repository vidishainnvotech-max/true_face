<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doors', function (Blueprint $table) {

            $table->id();

            // Ownership / Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Physical location context
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('gate_id')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();

            // Public identity
            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 120);

            // Door configuration
            $table->string('door_type', 32)
                ->default('pedestrian');

            $table->string('direction_mode', 24)
                ->default('bidirectional');

            $table->string('fail_mode', 24)
                ->default('fail_secure');

            $table->boolean('normally_locked')
                ->default(true);

            $table->boolean('requires_liveness')
                ->default(false);

            $table->boolean('anti_passback_enabled')
                ->default(false);

            $table->unsignedSmallInteger('held_open_timeout_seconds')
                ->default(30);

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
            $table->index('gate_id');
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
                'door_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'door_company_fk'
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
                'door_location_fk'
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

            // Gate scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'gate_id'
                ],
                'door_gate_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'location_id',
                        'id'
                    ]
                )
                ->on('gates')
                ->restrictOnDelete();

            // Building scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'building_id'
                ],
                'door_building_fk'
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
                'door_floor_fk'
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
                'door_zone_fk'
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
        Schema::dropIfExists('doors');
    }
};