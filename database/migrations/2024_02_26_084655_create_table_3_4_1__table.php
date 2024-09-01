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
        Schema::create('table_3_4_1_', function (Blueprint $table) {
            $table->id();
            $table->string('talaba_fish')->nullable();
            $table->string('tanlov_musoboqa_nomi')->nullable();
            $table->string('otkazilgan_joy_sana')->nullable();
            $table->string('fanlari_tanlov_musoqoqa')->nullable();
            $table->string('egallagan_orni')->nullable();
            $table->string('diplom_serya')->nullable();
            $table->string('diplom_raqam')->nullable();
            $table->text('izoh')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_3_4_1_');
    }
};
