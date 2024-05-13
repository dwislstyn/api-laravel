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
        Schema::create('employee_family', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id');
            $table->string('name', 255);
            $table->string('indentifier', 255);
            $table->string('job', 255);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('religion', ['Islam', 'Katolik', 'Budha', 'Protestan', 'Khonghucu']);
            $table->boolean('is_life');
            $table->boolean('is_divorced')->default(false);
            $table->enum('relation_status', ['Suami', 'Istri', 'Anak', 'Anak Sambung']);
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->date('created_at');
            $table->date('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('table_employee_family');
    }
};
