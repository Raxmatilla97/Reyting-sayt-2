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
        Schema::create('table_3_', function (Blueprint $table) {
            $table->id();
            $table->string('magistr_darajasi_bergan_otm')->nullable();
            $table->string('magistr_darajasi_seriya_raqam')->nullable();
            $table->string('mutaxasislik_nomi')->nullable();
            $table->string('ishga_qabul_raqam_sana')->nullable();         
            $table->string('asos_file')->nullable();              
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_3_');
    }
};
