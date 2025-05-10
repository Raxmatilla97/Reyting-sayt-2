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
        Schema::create('table_2_', function (Blueprint $table) {
            $table->id();
            $table->string('daraja_bergan_otm_nomi')->nullable();
            $table->string('phd_diplom_seryasi_raqami')->nullable();           
            $table->string('dsc_diplom_seryasi_va_raqami')->nullable();      
            $table->string('ixtisoslik_nomi')->nullable();
            $table->string('ishga_qabul_raqam_seryasi_va_sanasi')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_2_');
    }
};
