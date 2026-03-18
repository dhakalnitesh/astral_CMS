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
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();

            // Keep link to service
            $table->foreignId('service_id')->constrained();

            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            $table->string('project_name')->nullable();

            $table->decimal('base_price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};
