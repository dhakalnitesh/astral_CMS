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
        Schema::create('commissions', function (Blueprint $table) {
    $table->id();

    // Link to service, do not delete commission if service is deleted
    $table->foreignId('service_id')->constrained();

    $table->string('agent_name');

    $table->decimal('commission_percentage', 5, 2)->nullable();
    $table->decimal('commission_amount', 12, 2);

    $table->date('paid_date')->nullable();

    $table->softDeletes(); // allows recovering deleted commission records
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
