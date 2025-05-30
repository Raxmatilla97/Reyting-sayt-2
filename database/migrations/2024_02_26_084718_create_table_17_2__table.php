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
        Schema::create('table_17_2_', function (Blueprint $table) {
            $table->id();
            $table->string('guvohnoma_nomi')->nullable();
            $table->string('berilgan_sana')->nullable();
            $table->string('raqami')->nullable();
            $table->string('guvohnoma_url')->nullable();
            $table->string('huquq_egalari_soni')->nullable(); 
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_17_2_');
    }
};
