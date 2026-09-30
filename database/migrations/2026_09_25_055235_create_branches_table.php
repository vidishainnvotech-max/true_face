<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');

            $table->unsignedBigInteger('company_id');

            $table->unsignedBigInteger('legal_entity_id')
                ->nullable();

            $table->unsignedBigInteger('business_unit_id')
                ->nullable();

            $table->char('public_id', 26)
                ->unique();

            $table->string('code', 32)
                ->unique();

            $table->string('name', 160);

            $table->string('timezone', 64)
                ->default('UTC');

            $table->string('status', 24)
                ->default('active')
                ->index();

            $table->date('effective_from')
                ->nullable();

            $table->date('effective_to')
                ->nullable();

            $table->json('settings')
                ->nullable();

            $table->timestamps(6);

            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('legal_entity_id');
            $table->index('business_unit_id');

            $table->unique(
                ['tenant_id', 'company_id', 'id'],
                'branch_scope_unique'
            );

            // Tenant relationship
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();
                                        
            // Company relationship
            $table->foreign([
                'tenant_id',
                'company_id'
            ], 'branch_company_fk')
                ->references([
                    'tenant_id',
                    'id'
                ])
                ->on('companies')
                ->restrictOnDelete();

            // Legal Entity relationship
            $table->foreign([
                'tenant_id',
                'company_id',
                'legal_entity_id'
            ], 'branch_legal_entity_fk')
                ->references([
                    'tenant_id',
                    'company_id',
                    'id'
                ])
                ->on('legal_entities')
                ->restrictOnDelete();

            // Business Unit relationship
            $table->foreign([
                'tenant_id',
                'company_id',
                'business_unit_id'
            ], 'branch_business_unit_fk')
                ->references([
                    'tenant_id',
                    'company_id',
                    'id'
                ])
                ->on('business_units')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
