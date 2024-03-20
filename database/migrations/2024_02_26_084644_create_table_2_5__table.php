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
        Schema::create('table_2_5_', function (Blueprint $table) {
            $table->id();
            $table->string('fish')->nullable();           
            $table->string('talim_kodi')->nullable();           
            $table->string('talim_nomi')->nullable();           
            $table->string('hujjat_nomi_imzosi')->nullable();           
            $table->string('fanlar_nomi')->nullable();           
            $table->string('chet_tili_nomi')->nullable();           
            $table->string('talim_bosqichi')->nullable();           
            $table->string('talabalar_soni')->nullable();           
            $table->string('elekron_manzil')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_5_');
    }
};
