<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('tenants', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }

    public function up(): void
{
    Schema::create('tenants', function (Blueprint $table) {

        $table->id();

        $table->char('public_id', 26)->unique();

        $table->string('code', 32)->unique();

        $table->string('name', 160);

        $table->string('slug', 160)->unique();

        $table->string('status', 24)->default('active')->index();

        $table->string('isolation_mode', 24)->default('shared');

        $table->string('default_timezone', 64)->default('UTC');

        $table->string('default_locale', 12)->default('en');

        $table->char('country_code', 2)->nullable();

        $table->string('data_residency_region', 64)->nullable();

        $table->json('settings')->nullable();

        $table->timestamp('activated_at', 6)->nullable();

        $table->timestamp('suspended_at', 6)->nullable();

        $table->timestamps(6);

        $table->softDeletes('deleted_at', 6);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
