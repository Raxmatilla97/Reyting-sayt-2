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
        Schema::create('table_2_4_1_', function (Blueprint $table) {
            $table->id();
            $table->string('hujjat_nomi_sanasi')->nullable();
            $table->string('otm_talaba_fish')->nullable();
            $table->string('davlat_otm_nomi')->nullable();
            $table->string('mutaxasislik_nomi')->nullable();
            $table->string('xorijiy_talaba_fish')->nullable();
            $table->string('davlat_otm_nomi2')->nullable();
            $table->string('mutaxasislik_nomi2')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_4_1_');
    }
};
