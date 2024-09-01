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
        Schema::create('table_4_1_', function (Blueprint $table) {
            $table->id();
            $table->string('talaba_fish')->nullable();
            $table->string('talim_yonalishi')->nullable();
            $table->string('oqish_bosqichi')->nullable();
            $table->string('sport_klubi_nomi')->nullable();
            $table->string('sport_turi')->nullable();
            $table->string('sport_klubiga_azolik_sanasi')->nullable();
            $table->string('nechanchi_razryad')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_4_1_');
    }
};
