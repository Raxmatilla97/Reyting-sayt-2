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
        Schema::create('table_1_7_2_', function (Blueprint $table) {
            $table->id();
            $table->string('sohalar_buyurtma_nomi')->nullable();
            $table->string('sohalar_buyurtma_summasi')->nullable();
            $table->string('jami_summa')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('departament_id')->constrained('departments')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_7_2_');
    }
};
