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
        Schema::create('table_11_1_', function (Blueprint $table) {
            $table->id();
            $table->string('jurnal_nomi')->nullable();
            $table->string('nashr_yili')->nullable();
            $table->string('maqola_nomi')->nullable();
            $table->string('tili')->nullable();
            $table->string('index_url')->nullable();
            $table->string('iqtiboslar_soni')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_11_1_');
    }
};
