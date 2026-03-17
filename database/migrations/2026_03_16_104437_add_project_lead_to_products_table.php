<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('project_lead')->nullable()->change();
            $table->foreign('project_lead')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();
            $table->enum('status', ['pending', 'completed'])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['project_lead']);
            $table->string('project_lead')->change();
        });
    }
};
