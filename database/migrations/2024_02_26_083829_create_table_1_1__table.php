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
        Schema::create('table_1_1_', function (Blueprint $table) {
            $table->id();
            $table->string('daraja_bergan_otm_nomi')->nullable();
            $table->string('phd_diplom_seryasi')->nullable();
            $table->string('phd_diplom_raqami')->nullable();
            $table->string('dsc_diplom_seryasi')->nullable();
            $table->string('dsc_diplom_raqami')->nullable();
            $table->string('mutaxasislik_nomi')->nullable();
            $table->string('ishga_qabul_raqam_seryasi')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_1_');
    }
};
