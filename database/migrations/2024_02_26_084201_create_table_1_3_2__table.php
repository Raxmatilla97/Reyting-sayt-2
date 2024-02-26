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
        Schema::create('table_1_3_2_', function (Blueprint $table) {
            $table->id();
            $table->string('doktorlik_diplom_seryasi')->nullable();
            $table->string('doktorlik_diplom_raqami')->nullable();
            $table->string('ilmiy_unvon_seryasi')->nullable();
            $table->string('ilmiy_unvon_raqami')->nullable();
            $table->string('mutaxasisligi_nomi')->nullable();          
            $table->string('ishga_buyrug_rqami_seryasi')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_3_2_');
    }
};
