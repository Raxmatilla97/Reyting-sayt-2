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
        Schema::create('table_20_1_', function (Blueprint $table) {
            $table->id();
            $table->string('mualliflar_soni')->nullable();
            $table->string('jurnal_nomi')->nullable();
            $table->string('asos_url')->nullable();     
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_20_1_');
    }
};
