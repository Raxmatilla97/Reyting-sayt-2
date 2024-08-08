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
        Schema::create('table_2_3_1_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_oqituvchi_ismi')->nullable();
            $table->string('davlati_ish_joyi')->nullable();
            $table->string('mutaxasisligi')->nullable();
            $table->string('dars_beradigan_fani')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_3_1_');
    }
};
