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
        Schema::create('table_1_2_', function (Blueprint $table) {
            $table->id();
            $table->string('xorijiy_davlat_nomi')->nullable();
            $table->string('otm_nomi')->nullable();
            $table->string('mutaxasisligi')->nullable();
            $table->string('faoliyat_nomi')->nullable();
            $table->string('muddati')->nullable();
            $table->string('asos_file')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_2_');
    }
};
