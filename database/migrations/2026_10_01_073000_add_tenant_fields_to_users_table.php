<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Tenant to which this user belongs
            // NULL = Platform/Super Admin
            $table->foreignId('tenant_id')
                ->nullable()
                ->after('id')
                ->constrained('tenants')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            // Tenant-specific login username
            $table->string('username', 80)
                ->after('tenant_id');

            // Status of user account
            $table->string('status', 24)
                ->default('active')
                ->after('password');
        });

        // Username can be same in different tenants,
        // but should be unique within the same tenant.
        $tableName = 'users';

        Schema::table($tableName, function (Blueprint $table) {
            $table->unique(['tenant_id', 'username'], 'uq_users_tenant_username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('uq_users_tenant_username');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn([
                'tenant_id',
                'username',
                'status',
            ]);
        });
    }
};