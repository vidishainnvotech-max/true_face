<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {

            $table->id();

            // Ownership / Scope
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            // Optional Grade
            $table->unsignedBigInteger('grade_id')->nullable();

            // Public identity
            $table->char('public_id', 26)->unique();
            $table->string('code', 32)->unique();

            // Designation details
            $table->string('title', 160);
            $table->text('description')->nullable();

            $table->boolean('is_managerial')
                ->default(false);

            // Lifecycle
            $table->string('status', 24)
                ->default('active');

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('grade_id');
            $table->index('status');

            // Required composite key for scoped FK references
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'id'
                ],
                'designation_scope_unique'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'designation_company_fk'
            )
                ->references(
                    ['tenant_id', 'id']
                )
                ->on('companies')
                ->restrictOnDelete();

            // Grade scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'grade_id'
                ],
                'designation_grade_fk'
            )
                ->references(
                    [
                        'tenant_id',
                        'company_id',
                        'id'
                    ]
                )
                ->on('grades')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};