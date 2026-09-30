<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_units', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');

            $table->unsignedBigInteger('company_id');

            $table->unsignedBigInteger('parent_business_unit_id')
                ->nullable();

            $table->char('public_id', 26)
                ->unique();

            $table->string('code', 32)
                ->unique();

            $table->string('name', 160);

            $table->text('description')
                ->nullable();

            $table->unsignedSmallInteger('sort_order')
                ->default(0);

            $table->string('status', 24)
                ->default('active');

            $table->date('effective_from')
                ->nullable();

            $table->date('effective_to')
                ->nullable();

            $table->json('settings')
                ->nullable();

            $table->timestamps(6);

            $table->softDeletes('deleted_at', 6);

            /*
             * Supporting indexes / keys
             */

            $table->index('tenant_id');

            $table->index('company_id');

            $table->index([
                'tenant_id',
                'company_id'
            ]);

            // $table->index([
            //     'tenant_id',
            //     'company_id',
            //     'parent_business_unit_id'
            // ]);

            $table->index([
                'tenant_id',
                'company_id',
                'parent_business_unit_id'
            ], 'bu_parent_idx');

            /*
             * Composite key required for self-reference
             */

            $table->unique([
                'tenant_id',
                'company_id',
                'id'
            ]);

            /*
             * Tenant relationship
             */

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            /*
             * Company relationship
             * Proves tenant + company consistency
             */

            $table->foreign([
                'tenant_id',
                'company_id'
            ])
                ->references([
                    'tenant_id',
                    'id'
                ])
                ->on('companies')
                ->restrictOnDelete();

            /*
             * Parent Business Unit relationship
             * Proves same tenant + same company hierarchy
             */

            // $table->foreign([
            //     'tenant_id',
            //     'company_id',
            //     'parent_business_unit_id'
            // ])
            //     ->references([
            //         'tenant_id',
            //         'company_id',
            //         'id'
            //     ])
            //     ->on('business_units')
            //     ->restrictOnDelete();

            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'parent_business_unit_id'
                ],
                'bu_parent_fk'
            )
                ->references([
                    'tenant_id',
                    'company_id',
                    'id'
                ])
                ->on('business_units')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};
