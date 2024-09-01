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
        Schema::create('table_1_4_', function (Blueprint $table) {
            $table->id();
            $table->string('ish_joyi')->nullable();
            $table->string('ixtisoslik_shifri')->nullable();
            $table->string('ixtisoslik_nomi')->nullable();
            $table->string('disertatsiya_mavzusi')->nullable();
            $table->string('maxsus_kengash_shifri')->nullable();
            $table->string('ilmiy_unvon_olganlar')->nullable();
            $table->string('ilmiy_unvon_tasdiqlangan_sana')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_4_');
    }
};
