<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('role')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->integer('experience_years')->nullable();

            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('municipal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tole')->nullable();
            $table->string('address_line')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes(); // keep employee for historical purposes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};