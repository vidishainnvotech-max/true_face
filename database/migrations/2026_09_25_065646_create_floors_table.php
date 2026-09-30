<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('building_id');

            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 120);

            $table->smallInteger('level_number')->nullable();

            $table->unsignedSmallInteger('sort_order')
                ->default(0)
                ->index();

            $table->string('status', 24)
                ->default('active');

            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('building_id');

            // Required composite key for future scoped FK references
            $table->unique(
                ['tenant_id', 'company_id', 'location_id', 'building_id', 'id'],
                'floor_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'floor_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Building scoped FK
            $table->foreign(
                ['tenant_id', 'company_id', 'location_id', 'building_id'],
                'floor_building_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'location_id', 'id']
                )
                ->on('buildings')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};