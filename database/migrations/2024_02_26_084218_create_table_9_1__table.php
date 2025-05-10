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
        Schema::create('table_9_1_', function (Blueprint $table) {
            $table->id();
            $table->string('shifr_nomi')->nullable();
            $table->string('mualliflar')->nullable();
            $table->string('mualliflar_soni')->nullable();
            $table->string('monografiya_nomi')->nullable();
            $table->string('kengash_bayoni_sana')->nullable();
            $table->string('Nashiryot_nomi')->nullable();
            $table->string('doi_raqami')->nullable();
            $table->string('scopus_link_url')->nullable();         
            $table->string('asos_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_9_1_');
    }
};
