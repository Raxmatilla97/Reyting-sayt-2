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
        Schema::create('table_19_', function (Blueprint $table) {
            $table->id();
            $table->string('otm_nomi')->nullable();
            $table->string('ik_raqami')->nullable();
            $table->string('shifr_nomi')->nullable();
            $table->string('disertatsiya_mavzusi')->nullable();       
            $table->string('oak_sana')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_19_');
    }
};
