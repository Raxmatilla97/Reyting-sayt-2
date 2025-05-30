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
        Schema::create('table_7_', function (Blueprint $table) {
            $table->id();
            $table->string('kurs_nomi')->nullable();
            $table->string('xorijiy_davlat_nomi')->nullable();
            $table->string('sertifikat_raqam_sana')->nullable();
            $table->string('link_url')->nullable();       
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_7_');
    }
};
