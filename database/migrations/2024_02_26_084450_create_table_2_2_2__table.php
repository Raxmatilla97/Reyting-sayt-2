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
        Schema::create('table_2_2_2_', function (Blueprint $table) {
            $table->id();
            $table->string('ixtisoslik_shifri')->nullable();
            $table->string('qollanma_mualliflar_soni')->nullable();
            $table->string('qollanma_nomi')->nullable();
            $table->string('qollanma_guvohnomasi')->nullable();
            $table->string('qollanma_reestr_raqami')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_2_2_');
    }
};
