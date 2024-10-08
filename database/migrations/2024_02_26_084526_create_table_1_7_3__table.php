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
        Schema::create('table_1_7_3_', function (Blueprint $table) {
            $table->id();
            $table->string('davlat_grant_mavzusi')->nullable();
            $table->string('davlat_grant_summasi')->nullable();
            $table->string('jami_summa')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_7_3_');
    }
};
