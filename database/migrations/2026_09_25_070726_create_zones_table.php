<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('location_id');

            $table->unsignedBigInteger('building_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('parent_zone_id')->nullable();

            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 120);

            $table->string('zone_type', 24)
                ->default('general');

            $table->boolean('is_restricted')
                ->default(false)
                ->index();

            $table->unsignedInteger('capacity')->nullable();

            $table->string('status', 24)
                ->default('active');

            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('location_id');
            $table->index('building_id');
            $table->index('floor_id');
            $table->index('parent_zone_id');
            $table->index('status');

            // Required composite key for future scoped FK references
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'building_id',
                    'floor_id',
                    'id'
                ],
                'zone_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'zone_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Location scoped FK
            $table->foreign(
                ['tenant_id', 'company_id', 'location_id'],
                'zone_location_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('locations')
                ->restrictOnDelete();

            // Building scoped FK
            $table->foreign(
                ['tenant_id', 'company_id', 'location_id', 'building_id'],
                'zone_building_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'location_id', 'id']
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
                'zone_floor_fk'
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

            // Parent Zone scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'location_id',
                    'building_id',
                    'floor_id',
                    'parent_zone_id'
                ],
                'zone_parent_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'location_id',
                        'building_id',
                        'floor_id',
                        'id'
                    ]
                )
                ->on('zones')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};


