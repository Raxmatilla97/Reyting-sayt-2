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
        Schema::create('table_22_', function (Blueprint $table) {
            $table->id();
            $table->string('xujjat_nomi_sana')->nullable();
            $table->string('talaba_fish')->nullable();
            $table->string('otm_nomi')->nullable();
            $table->string('musaxasislik')->nullable();
            $table->string('cspu_talaba_fish')->nullable();
            $table->string('cspu_musaxasislik')->nullable();        
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_22_');
    }
};
