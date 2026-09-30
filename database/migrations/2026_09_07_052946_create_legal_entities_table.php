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
        Schema::create('legal_entities', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('tenant_id');
    $table->unsignedBigInteger('company_id');

    $table->char('public_id', 26)->unique();

    $table->string('code', 32)->unique();
    $table->string('legal_name', 200);

    $table->string('registration_number', 80)->nullable();
    $table->string('tax_identifier', 80)->nullable();

    $table->string('email', 190)->nullable();
    $table->string('phone', 32)->nullable();

    $table->string('address_line_1', 190)->nullable();
    $table->string('address_line_2', 190)->nullable();

    $table->string('city', 100)->nullable();
    $table->string('state_code', 32)->nullable();

    $table->char('country_code', 2)->default('IN');
    $table->string('postal_code', 20)->nullable();

    $table->string('status', 24)->default('active')->index();

    $table->date('effective_from')->nullable();
    $table->date('effective_to')->nullable();

    $table->json('settings')->nullable();

    $table->timestamps(6);
    // $table->softDeletes(6);
    $table->softDeletes('deleted_at', 6);

    $table->foreign('tenant_id')
        ->references('id')
        ->on('tenants')
        ->restrictOnDelete();

    $table->foreign(['tenant_id', 'company_id'])
        ->references(['tenant_id', 'id'])
        ->on('companies')
        ->restrictOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entities');
    }
};
