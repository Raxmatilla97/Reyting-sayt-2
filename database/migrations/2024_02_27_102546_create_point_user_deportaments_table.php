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
            // Bu yerda senga yuborgan migratsiyalardagi tablelar relation qilib yoziladi!
            $table->foreignId('table_2_id')->nullable()->constrained('table_2_')->onDelete('cascade');
            $table->foreignId('table_3_id')->nullable()->constrained('table_3_')->onDelete('cascade');
            $table->foreignId('table_4_id')->nullable()->constrained('table_4_')->onDelete('cascade');
            $table->foreignId('table_5_id')->nullable()->constrained('table_5_')->onDelete('cascade');
            $table->foreignId('table_6_id')->nullable()->constrained('table_6_')->onDelete('cascade');
            $table->foreignId('table_7_id')->nullable()->constrained('table_7_')->onDelete('cascade');
            $table->foreignId('table_8_1_id')->nullable()->constrained('table_8_1_')->onDelete('cascade');
            $table->foreignId('table_8_2_id')->nullable()->constrained('table_8_2_')->onDelete('cascade');
            $table->foreignId('table_9_1_id')->nullable()->constrained('table_9_1_')->onDelete('cascade');
            $table->foreignId('table_9_2_id')->nullable()->constrained('table_9_2_')->onDelete('cascade');
            $table->foreignId('table_10_1_id')->nullable()->constrained('table_10_1_')->onDelete('cascade');
            $table->foreignId('table_10_2_id')->nullable()->constrained('table_10_2_')->onDelete('cascade');
            $table->foreignId('table_10_3_id')->nullable()->constrained('table_10_3_')->onDelete('cascade');
            $table->foreignId('table_11_1_id')->nullable()->constrained('table_11_1_')->onDelete('cascade');
            $table->foreignId('table_11_2_id')->nullable()->constrained('table_11_2_')->onDelete('cascade');
            $table->foreignId('table_11_3_id')->nullable()->constrained('table_11_3_')->onDelete('cascade');
            $table->foreignId('table_12_id')->nullable()->constrained('table_12_')->onDelete('cascade');
            $table->foreignId('table_13_id')->nullable()->constrained('table_13_')->onDelete('cascade');
            $table->foreignId('table_14_1_id')->nullable()->constrained('table_14_1_')->onDelete('cascade');
            $table->foreignId('table_14_2_id')->nullable()->constrained('table_14_2_')->onDelete('cascade');
            $table->foreignId('table_14_3_id')->nullable()->constrained('table_14_3_')->onDelete('cascade');
            $table->foreignId('table_15_1_id')->nullable()->constrained('table_15_1_')->onDelete('cascade');
            $table->foreignId('table_15_2_id')->nullable()->constrained('table_15_2_')->onDelete('cascade');
            $table->foreignId('table_16_id')->nullable()->constrained('table_16_')->onDelete('cascade');
            $table->foreignId('table_17_1_id')->nullable()->constrained('table_17_1_')->onDelete('cascade');
            $table->foreignId('table_17_2_id')->nullable()->constrained('table_17_2_')->onDelete('cascade');
            $table->foreignId('table_18_1_id')->nullable()->constrained('table_18_1_')->onDelete('cascade');
            $table->foreignId('table_18_2_id')->nullable()->constrained('table_18_2_')->onDelete('cascade');
            $table->foreignId('table_18_3_id')->nullable()->constrained('table_18_3_')->onDelete('cascade');
            $table->foreignId('table_18_3_a_id')->nullable()->constrained('table_18_3_a_')->onDelete('cascade');
            $table->foreignId('table_19_id')->nullable()->constrained('table_19_')->onDelete('cascade');
            $table->foreignId('table_20_1_id')->nullable()->constrained('table_20_1_')->onDelete('cascade');
            $table->foreignId('table_20_2_id')->nullable()->constrained('table_20_2_')->onDelete('cascade');
            $table->foreignId('table_20_3_id')->nullable()->constrained('table_20_3_')->onDelete('cascade');
            $table->foreignId('table_21_1_id')->nullable()->constrained('table_21_1_')->onDelete('cascade');
            $table->foreignId('table_21_2_id')->nullable()->constrained('table_21_2_')->onDelete('cascade');
            $table->foreignId('table_22_id')->nullable()->constrained('table_22_')->onDelete('cascade');
            $table->foreignId('table_23_id')->nullable()->constrained('table_23_')->onDelete('cascade');
            $table->foreignId('table_24_id')->nullable()->constrained('table_24_')->onDelete('cascade');
   
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