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
        Schema::create('table_5_', function (Blueprint $table) {
            $table->id();
            $table->string('ixtisoslik_shifr_nomi')->nullable();
            $table->string('oquv_mualliflar_soni')->nullable();
            $table->string('oquv_qollanma_nomi')->nullable();
            $table->string('guvohnoma_raqam_sana')->nullable();
            $table->string('reestr_raqami')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_5_');
    }
};
