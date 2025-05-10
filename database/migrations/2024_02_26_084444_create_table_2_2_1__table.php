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
        Schema::create('table_11_2_a_', function (Blueprint $table) {
            $table->id();
            $table->string('ixtisoslik_shifri')->nullable();
            $table->string('darslik_mualliflar_soni')->nullable();
            $table->string('darslik_nomi')->nullable();
            $table->string('darslik_guvohnomasi')->nullable();
            $table->string('darslik_reestr_raqami')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_11_2_a_');
    }
};
