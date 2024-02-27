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
        Schema::create('table_2_4_2_b_', function (Blueprint $table) {
            $table->id();
            $table->string('hujjat_nomi_sanasi')->nullable();
            $table->string('ism_sharifi')->nullable();
            $table->string('xorijiy_davlat_otm_nomi')->nullable();
            $table->string('mutaxasislik_nomi')->nullable();
            $table->string('loyha_nomi')->nullable();
            $table->string('seminar_nomi')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_4_2_b_');
    }
};
