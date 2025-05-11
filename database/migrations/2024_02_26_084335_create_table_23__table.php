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
        Schema::create('table_23_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_oqituvchi_fish')->nullable();
            $table->string('davlat_asosy_ish')->nullable();
            $table->string('mutaxasislik')->nullable();
            $table->string('dars_bebradigan_fan')->nullable();
            $table->string('scopus_id')->nullable();       
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_23_');
    }
};
