<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('location_id');

            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();
            $table->string('name', 160);

            $table->text('description')->nullable();

            $table->string('status', 24)
                ->default('active')
                ->index();

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            $table->json('settings')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('location_id');

            // Scope key for future composite FK references
            $table->unique(
                ['tenant_id', 'company_id', 'location_id', 'id'],
                'building_scope_unique'
            );

            // Tenant
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company
            $table->foreign(
                ['tenant_id', 'company_id'],
                'building_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Location
            $table->foreign(
                ['tenant_id', 'company_id', 'location_id'],
                'building_location_fk'
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
        Schema::dropIfExists('buildings');
    }
};