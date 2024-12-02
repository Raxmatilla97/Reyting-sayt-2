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
            // is_kpi_reviewer ni boolean qilish
            $table->boolean('is_kpi_reviewer')->default(false)->change();

            // KPI fakultet uchun yangi ustun
            $table->unsignedBigInteger('kpi_faculty_id')->nullable()->after('department_id');

            // JSON formatdagi kategoriyalarni o'zgartirish
            $table->json('kpi_review_categories')->nullable()->change();

            // Foreign key
            $table->foreign('kpi_faculty_id')->references('id')->on('faculties')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kpi_faculty_id']);
            $table->dropColumn('kpi_faculty_id');
            // O'zgartirilgan ustunlarni avvalgi holatiga qaytarish
            $table->integer('is_kpi_reviewer')->default(0)->change();
        });
    }
};
