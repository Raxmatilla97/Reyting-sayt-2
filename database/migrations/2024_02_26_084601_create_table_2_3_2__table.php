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
        Schema::create('table_2_3_2_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_talaba_ismi')->nullable();
            $table->string('davlati')->nullable();
            $table->string('talim_yonalishi')->nullable();
            $table->string('magister_shifri_nomi')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_3_2_');
    }
};
