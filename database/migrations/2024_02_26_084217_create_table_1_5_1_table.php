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
        Schema::create('table_1_5_1_', function (Blueprint $table) {
            $table->id();
            $table->string('jurnalning_nomi')->nullable();
            $table->string('jurnal_nashr_yili_oyi')->nullable();
            $table->string('maqolaning_nomi')->nullable();
            $table->string('maqola_tili')->nullable();
            $table->string('google_schoolar_url')->nullable();
            $table->string('google_schoolar_iqtiboslar')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_5_1_');
    }
};
