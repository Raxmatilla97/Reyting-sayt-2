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
        Schema::create('table_1_9_3_', function (Blueprint $table) {
            $table->id();
            $table->string('otm_nomi')->nullable();
            $table->string('asosiy_shtatdagi_prof_oqituv')->nullable();
            $table->string('olingan_guvohnomalar')->nullable();
            $table->string('mualliflar_soni')->nullable();
            $table->string('berilgan_sana')->nullable();
            $table->string('qayd_raqami')->nullable();
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_9_3_');
    }
};
