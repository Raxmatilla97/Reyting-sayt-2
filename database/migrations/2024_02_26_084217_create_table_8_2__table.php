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
        Schema::create('table_8_2_', function (Blueprint $table) {
            $table->id();
            $table->string('mutaxasislik_kodi_nomi')->nullable();
            $table->string('rektor_buyruq_sana')->nullable();
            $table->string('fanlar_nomi')->nullable();
            $table->string('hemis_url')->nullable();          
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_8_2_');
    }
};
