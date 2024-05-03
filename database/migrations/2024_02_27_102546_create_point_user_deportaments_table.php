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
        Schema::create('point_user_deportaments', function (Blueprint $table) {
            $table->id();
            // Jurnallarni relation orqali bog'lash
            $table->foreignId('table_1_1_id')->nullable()->constrained('table_1_1_')->onDelete('cascade');  
            $table->foreignId('table_1_2_id')->nullable()->constrained('table_1_2_')->onDelete('cascade');  
            $table->foreignId('table_1_3_1_id')->nullable()->constrained('table_1_3_1_')->onDelete('cascade');  
            $table->foreignId('table_1_3_2_id')->nullable()->constrained('table_1_3_2_')->onDelete('cascade');  
            $table->foreignId('table_1_4_id')->nullable()->constrained('table_1_4_')->onDelete('cascade');  
            $table->foreignId('table_1_5_id')->nullable()->constrained('table_1_5_')->onDelete('cascade');            
            $table->foreignId('table_1_6_1_id')->nullable()->constrained('table_1_6_1_')->onDelete('cascade');  
            $table->foreignId('table_1_6_2_id')->nullable()->constrained('table_1_6_2_')->onDelete('cascade');  
            $table->foreignId('table_1_9_1_id')->nullable()->constrained('table_1_9_1_')->onDelete('cascade');  
            $table->foreignId('table_1_9_2_id')->nullable()->constrained('table_1_9_2_')->onDelete('cascade');  
            $table->foreignId('table_1_9_3_id')->nullable()->constrained('table_1_9_3_')->onDelete('cascade');  
            $table->foreignId('table_2_2_1_id')->nullable()->constrained('table_2_2_1_')->onDelete('cascade');  
            $table->foreignId('table_2_2_2_id')->nullable()->constrained('table_2_2_2_')->onDelete('cascade');  
            $table->foreignId('table_2_4_2_id')->nullable()->constrained('table_2_4_2_')->onDelete('cascade');
            // Departament true
            $table->foreignId('table_1_7_1_id')->nullable()->constrained('table_1_7_1_')->onDelete('cascade');  
            $table->foreignId('table_1_7_2_id')->nullable()->constrained('table_1_7_2_')->onDelete('cascade');  
            $table->foreignId('table_1_7_3_id')->nullable()->constrained('table_1_7_3_')->onDelete('cascade');  
            $table->foreignId('table_2_3_1_id')->nullable()->constrained('table_2_3_1_')->onDelete('cascade');  
            $table->foreignId('table_2_3_2_id')->nullable()->constrained('table_2_3_2_')->onDelete('cascade');  
            $table->foreignId('table_2_4_1_id')->nullable()->constrained('table_2_4_1_')->onDelete('cascade');  
            $table->foreignId('table_2_4_2_b_id')->nullable()->constrained('table_2_4_2_b_')->onDelete('cascade');  
            $table->foreignId('table_2_5_id')->nullable()->constrained('table_2_5_')->onDelete('cascade');  
            $table->foreignId('table_3_4_1_id')->nullable()->constrained('table_3_4_1_')->onDelete('cascade');  
            $table->foreignId('table_3_4_2_id')->nullable()->constrained('table_3_4_2_')->onDelete('cascade');  
            $table->foreignId('table_4_1_id')->nullable()->constrained('table_4_1_')->onDelete('cascade'); 
            // end
            $table->boolean('departament_info')->default(false);
            $table->boolean('status')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('temporary_files_id')->nullable()->constrained('temporary_files'); 
            $table->foreignId('departament_id')->nullable()->constrained('departments')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_user_deportaments');
    }
};
