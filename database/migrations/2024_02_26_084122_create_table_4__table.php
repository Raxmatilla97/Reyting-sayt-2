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
        Schema::create('table_4_', function (Blueprint $table) {
            $table->id();
            $table->string('ixtisoslik_shifr_nomi_nomi')->nullable();
            $table->string('mualiflar_soni')->nullable();
            $table->string('darslik_nomi')->nullable();
            $table->string('guvohnoma_raqami')->nullable();
            $table->string('darslik_restr_raqami')->nullable();        
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_4_');
    }
};
