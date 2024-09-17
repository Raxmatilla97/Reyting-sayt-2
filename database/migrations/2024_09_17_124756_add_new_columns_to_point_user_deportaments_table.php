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
        Schema::table('point_user_deportaments', function (Blueprint $table) {
            // Yangi ustunlarni qo'shish
            $table->foreignId('table_1_3_1_a_id')->nullable()->after('table_1_3_1_id');
            $table->foreignId('table_1_3_1_b_id')->nullable()->after('table_1_3_1_a_id');
            $table->foreignId('table_1_3_2_a_id')->nullable()->after('table_1_3_2_id');
            $table->foreignId('table_1_3_2_b_id')->nullable()->after('table_1_3_2_a_id');

            // Foreign key'larni qo'shish
            $table->foreign('table_1_3_1_a_id')->references('id')->on('table_1_3_1_a_')->onDelete('cascade');
            $table->foreign('table_1_3_1_b_id')->references('id')->on('table_1_3_1_b_')->onDelete('cascade');
            $table->foreign('table_1_3_2_a_id')->references('id')->on('table_1_3_2_a_')->onDelete('cascade');
            $table->foreign('table_1_3_2_b_id')->references('id')->on('table_1_3_2_b_')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_user_deportaments', function (Blueprint $table) {
             // Foreign key'larni o'chirish
             $table->dropForeign(['table_1_3_1_a_id']);
             $table->dropForeign(['table_1_3_1_b_id']);
             $table->dropForeign(['table_1_3_2_a_id']);
             $table->dropForeign(['table_1_3_2_b_id']);

             // Ustunlarni o'chirish
             $table->dropColumn('table_1_3_1_a_id');
             $table->dropColumn('table_1_3_1_b_id');
             $table->dropColumn('table_1_3_2_a_id');
             $table->dropColumn('table_1_3_2_b_id');
        });
    }
};
