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
        Schema::create('table_2_4_2_', function (Blueprint $table) {
            $table->id();
            $table->string('hujjat_nomi_sanasi')->nullable();
            $table->string('xorijiy_davlat_nomi')->nullable();
            $table->string('talim_yonalishi')->nullable();
            $table->string('xorijiy_va_hamkor')->nullable();
            $table->string('seminar_knfrensiya_nomi')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_4_2_');
    }
};
