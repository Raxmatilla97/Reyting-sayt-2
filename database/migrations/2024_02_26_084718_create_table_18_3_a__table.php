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
        Schema::create('table_18_3_a_', function (Blueprint $table) {
            $table->id();
            $table->string('diplom_seriya_raqam')->nullable();
            $table->string('muassasa_nomi')->nullable();
            $table->string('shifr_nomi')->nullable();
            $table->string('buyruq_raqami_sanasi')->nullable();       
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_18_3_a_');
    }
};
