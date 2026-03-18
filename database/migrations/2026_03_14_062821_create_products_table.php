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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Category of the product; keep product even if category is deleted
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('code')->nullable();

            // Project lead (employee); null if employee deleted
            $table->unsignedBigInteger('project_lead')->nullable();
            $table->foreign('project_lead')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();

            $table->integer('total_developers')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes(); // allows soft deletion for historical tracking
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};