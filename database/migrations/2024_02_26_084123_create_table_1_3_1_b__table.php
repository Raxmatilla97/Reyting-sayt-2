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
        Schema::create('table_1_3_1_b_', function (Blueprint $table) {
            $table->id();
            $table->string('prof_dip_serya')->nullable();
            $table->string('prof_dip_raqam')->nullable();
            $table->string('ishga_raq_sana')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_3_1_b_');
    }
};
