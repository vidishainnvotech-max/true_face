
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_hierarchy', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('company_id');

            $table->unsignedBigInteger('ancestor_department_id');
            $table->unsignedBigInteger('descendant_department_id');

            $table->unsignedSmallInteger('depth')->index();

            $table->timestamps(6);

            // Prevent duplicate ancestor-descendant relationships
            $table->unique(
                [
                    'tenant_id',
                    'company_id',
                    'ancestor_department_id',
                    'descendant_department_id'
                ],
                'dept_hierarchy_unique'
            );

            // Indexes
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index(
                'descendant_department_id',
                'dept_hierarchy_desc_idx'
            );

            // Tenant FK
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->restrictOnDelete();

            // Company scoped FK
            $table->foreign(
                ['tenant_id', 'company_id'],
                'dept_hierarchy_company_fk'
            )
                ->references(['tenant_id', 'id'])
                ->on('companies')
                ->restrictOnDelete();

            // Ancestor department scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'ancestor_department_id'
                ],
                'dept_hierarchy_ancestor_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('departments')
                ->restrictOnDelete();

            // Descendant department scoped FK
            $table->foreign(
                [
                    'tenant_id',
                    'company_id',
                    'descendant_department_id'
                ],
                'dept_hierarchy_descendant_fk'
            )
                ->references(
                    ['tenant_id', 'company_id', 'id']
                )
                ->on('departments')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_hierarchy');
    }
};