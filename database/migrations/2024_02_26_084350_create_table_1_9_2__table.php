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
        Schema::create('table_1_9_2_', function (Blueprint $table) {
            $table->id();
            $table->string('otmlar_nomi')->nullable();            
            $table->string('asosiy_statdagi_professorlar')->nullable();
            $table->string('ixtiro_model_uchun_patent')->nullable();
            $table->string('berilgan_sanasi')->nullable();
            $table->string('qayd_raqami')->nullable();         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_9_2_');
    }
};
