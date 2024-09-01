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
        Schema::create('table_3_4_2_', function (Blueprint $table) {
            $table->id();
            $table->string('talaba_fish')->nullable();
            $table->string('respublika_tanlov_nomi')->nullable();
            $table->string('otkazilgan_joy_sana')->nullable();
            $table->string('musobaqalar_nomi')->nullable();
            $table->string('egallagan_orni')->nullable();
            $table->string('diplom_seryasi')->nullable();
            $table->string('diplom_raqami')->nullable();
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
        Schema::dropIfExists('table_3_4_2_');
    }
};
