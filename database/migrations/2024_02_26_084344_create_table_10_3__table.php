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
        Schema::create('table_10_3_', function (Blueprint $table) {
            $table->id();
            $table->string('davlat_nomi')->nullable();
            $table->string('konfrrensiya_nomi')->nullable();
            $table->string('maqola_nomi')->nullable();
            $table->string('Nashr_yili_betlari')->nullable();
            $table->string('url_link')->nullable();
            $table->string('index_url')->nullable();
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
        Schema::dropIfExists('table_10_3_');
    }
};
