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
        Schema::table('users', function (Blueprint $table) {
            // Avval ustunlar mavjud emasligini tekshiramiz va qo'shamiz
            if (!Schema::hasColumn('users', 'is_kpi_reviewer')) {
                $table->boolean('is_kpi_reviewer')->default(false);
            }
            if (!Schema::hasColumn('users', 'kpi_review_categories')) {
                $table->json('kpi_review_categories')->nullable();
            }
            if (!Schema::hasColumn('users', 'kpi_faculty_id')) {
                $table->unsignedBigInteger('kpi_faculty_id')->nullable()->after('department_id');
                $table->foreign('kpi_faculty_id')->references('id')->on('faculties')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Foreign key ni o'chiramiz
            if (Schema::hasColumn('users', 'kpi_faculty_id')) {
                $table->dropForeign(['kpi_faculty_id']);
                $table->dropColumn('kpi_faculty_id');
            }

            // Ustunlarni o'chiramiz
            if (Schema::hasColumn('users', 'is_kpi_reviewer')) {
                $table->dropColumn('is_kpi_reviewer');
            }
            if (Schema::hasColumn('users', 'kpi_review_categories')) {
                $table->dropColumn('kpi_review_categories');
            }
        });
    }
};
