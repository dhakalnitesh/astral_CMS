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
        Schema::create('service_charges', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')->constrained();

            $table->decimal('installation_charge', 12, 2)->default(0);
            $table->decimal('hosting_charge', 12, 2)->default(0);
            $table->decimal('server_charge', 12, 2)->default(0);
            $table->decimal('maintenance_charge', 12, 2)->default(0);

            $table->boolean('hosting_is_recurring')->default(true);
            $table->integer('hosting_cycle_months')->default(12);

            $table->boolean('maintenance_is_recurring')->default(true);
            $table->integer('maintenance_cycle_months')->default(12);

            $table->decimal('hosting_increase_percentage', 5, 2)->default(5);
            $table->integer('hosting_increase_after_months')->default(36);

            $table->decimal('renewal_charge', 12, 2)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_charges');
    }
};
