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
            $table->dropForeign(['table_1_3_1_id']);
            $table->dropForeign(['table_1_3_2_id']);
            $table->dropColumn('table_1_3_1_id');
            $table->dropColumn('table_1_3_2_id');
        });

        Schema::dropIfExists('table_1_3_1_');
        Schema::dropIfExists('table_1_3_2_');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_user_deportaments', function (Blueprint $table) {
            $table->foreignId('table_1_3_1_id')->nullable()->constrained('table_1_3_1_')->onDelete('cascade');
            $table->foreignId('table_1_3_2_id')->nullable()->constrained('table_1_3_2_')->onDelete('cascade');
        });
    }
};
