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
            $table->foreignId('table_1_1_id')->nullable()->constrained('table_2_')->onDelete('cascade');
            $table->foreignId('table_1_2_id')->nullable()->constrained('table_3_')->onDelete('cascade');
            $table->foreignId('table_1_4_id')->nullable()->constrained('table_8_1_')->onDelete('cascade');
            $table->foreignId('table_1_5_1_id')->nullable()->constrained('table_8_2_')->onDelete('cascade');
            $table->foreignId('table_1_5_1_a_id')->nullable()->constrained('table_9_1_')->onDelete('cascade');
            $table->foreignId('table_1_6_1_id')->nullable()->constrained('table_9_2_')->onDelete('cascade');
            $table->foreignId('table_1_6_1_a_id')->nullable()->constrained('table_10_1_')->onDelete('cascade');
            $table->foreignId('table_1_6_2_id')->nullable()->constrained('table_10_2_')->onDelete('cascade');
            $table->foreignId('table_1_9_1_id')->nullable()->constrained('table_10_3_')->onDelete('cascade');
            $table->foreignId('table_1_9_2_id')->nullable()->constrained('table_11_1_')->onDelete('cascade');
            $table->foreignId('table_1_9_3_id')->nullable()->constrained('table_11_2_')->onDelete('cascade');
            $table->foreignId('table_2_2_1_id')->nullable()->constrained('table_11_2_a_')->onDelete('cascade');
            $table->foreignId('table_2_2_2_id')->nullable()->constrained('table_12_')->onDelete('cascade');
            $table->foreignId('table_2_4_2_id')->nullable()->constrained('table_13_')->onDelete('cascade');
            // Departament true
            $table->foreignId('table_1_7_1_id')->nullable()->constrained('table_22_')->onDelete('cascade');
            $table->foreignId('table_1_7_2_id')->nullable()->constrained('table_23_')->onDelete('cascade');
            $table->foreignId('table_1_7_3_id')->nullable()->constrained('table_24_')->onDelete('cascade');
            $table->foreignId('table_2_3_1_id')->nullable()->constrained('table_14_1_')->onDelete('cascade');
            $table->foreignId('table_2_3_2_id')->nullable()->constrained('table_14_2_')->onDelete('cascade');
            $table->foreignId('table_2_4_1_id')->nullable()->constrained('table_14_3_')->onDelete('cascade');
            $table->foreignId('table_2_4_2_b_id')->nullable()->constrained('table_15_1_')->onDelete('cascade');
            $table->foreignId('table_2_5_id')->nullable()->constrained('table_15_2_')->onDelete('cascade');
            $table->foreignId('table_3_4_1_id')->nullable()->constrained('table_16_')->onDelete('cascade');
            $table->foreignId('table_3_4_2_id')->nullable()->constrained('table_17_1_')->onDelete('cascade');
            $table->foreignId('table_4_1_id')->nullable()->constrained('table_17_2_')->onDelete('cascade');
            $table->year('year')->nullable(); // Yil ustuni
            $table->text('arizaga_javob')->nullable();

            // end
            $table->float('point')->default(0);
            $table->boolean('departament_info')->default(false);
            $table->boolean('status')->nullable(); // null bu hali baholanmagan agar 1 bo'lsa bu baholanganligini anglatadi. 0 esa rad etilganligini
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
