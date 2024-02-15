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
        Schema::create('temporary_files', function (Blueprint $table) {
            $table->id();
            $table->string('folder')->nullable();
            $table->string('filename')->nullable();          
            $table->string('site_url')->nullable(); 
            $table->string('ariza_holati')->nullable(); 
            $table->date('date_created')->nullable();
            $table->text('arizaga_javob')->nullable();
            $table->text('malumot_haqida')->nullable(); 
            $table->integer('points')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');          
            $table->foreignId('category_id')->nullable()->constrained('categories');          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_files');
    }
};
