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
        Schema::create('table_8_1_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_til_bilish_daraja')->nullable();
            $table->string('xorijiy_til_turi')->nullable();
            $table->string('sertifikat_nomi')->nullable();
            $table->string('sertifikat_sanasi_raqami')->nullable();
            $table->string('sertifikat_muddati')->nullable();          
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_8_1_');
    }
};
