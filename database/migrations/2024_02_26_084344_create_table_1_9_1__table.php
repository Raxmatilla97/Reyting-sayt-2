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
        Schema::create('table_1_9_1_', function (Blueprint $table) {
            $table->id();
            $table->string('ixtisoslik_shifri')->nullable();
            $table->string('mualliflar_soni')->nullable();
            $table->string('monograf_mualliflar_soni')->nullable();
            $table->string('monograf_nomi')->nullable();
            $table->string('monograf_kengash_bayoni')->nullable();
            $table->string('nashryot_nomi')->nullable();
            $table->string('natlib_isbn_raqami')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_1_9_1_');
    }
};
