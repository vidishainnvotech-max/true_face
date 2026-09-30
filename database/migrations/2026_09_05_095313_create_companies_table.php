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
        Schema::create('companies', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tenant_id')
                ->unique()
                ->constrained('tenants')
                ->restrictOnDelete();
                
            $table->unique(['tenant_id', 'id']);

            $table->char('public_id', 26)
                ->unique();

            $table->string('code', 32)
                ->unique();

            $table->string('name', 160);

            $table->string('legal_name', 200)
                ->nullable();

            $table->string('trade_name', 160)
                ->nullable();

            $table->string('slug', 160)
                ->unique();

            $table->string('email', 190)
                ->nullable();

            $table->string('phone', 32)
                ->nullable();

            $table->string('website', 255)
                ->nullable();

            $table->string('timezone', 64)
                ->default('UTC');

            $table->char('default_currency', 3)
                ->default('INR');

            $table->char('country_code', 2)
                ->default('IN');

            $table->unsignedTinyInteger('fiscal_year_start_month')
                ->default(4);

            $table->string('status', 24)
                ->default('active')
                ->index();

            $table->json('settings')
                ->nullable();

            $table->date('effective_from')
                ->nullable();

            $table->date('effective_to')
                ->nullable();

            $table->timestamps(6);

            // $table->softDeletes(6);
            $table->softDeletes('deleted_at', 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};