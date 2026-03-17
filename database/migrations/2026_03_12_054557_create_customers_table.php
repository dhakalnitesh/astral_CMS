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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            $table->string('name'); // required
            $table->string('email')->unique(); // required
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // required
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->nullable();

            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('municipal_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('street')->nullable();
            $table->string('tole')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Optional: for safe deletion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
