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
        Schema::create('table_9_2_', function (Blueprint $table) {
            $table->id();
            $table->string('shifri_nomi')->nullable();
            $table->string('muallif_fish')->nullable();
            $table->string('mualliflar_soni')->nullable();
            $table->string('monografiya_nomi')->nullable();
            $table->string('kengash_bayoni_sana')->nullable();
            $table->string('nashiriyot_nomi')->nullable();
            $table->string('isn_raqam')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_9_2_');
    }
};
