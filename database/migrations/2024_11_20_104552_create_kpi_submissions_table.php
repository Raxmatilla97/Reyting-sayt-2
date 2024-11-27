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
        Schema::create('kpi_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inspector_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('kpi_criteria');
            $table->string('category');
            $table->text('description');
            $table->string('proof_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'apilation'])->default('pending');
            $table->text('apilation_message')->nullable();
            $table->float('points')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_submissions');
    }
};
