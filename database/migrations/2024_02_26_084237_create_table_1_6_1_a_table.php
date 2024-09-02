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
        Schema::create('table_1_6_1_a_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_jirnal_davlat_nomi')->nullable();
            $table->string('ilmiy_jurnal_nomi')->nullable();
            $table->string('ilmiy_maqola_nomi')->nullable();
            $table->string('nashr_yili_betlari')->nullable();
            $table->string('url_manzili')->nullable();
            $table->string('mualliflar_soni')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_6_1_a_');
    }
};
