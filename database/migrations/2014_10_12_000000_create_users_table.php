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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('employee_id_number')->nullable();           
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('gender_code')->nullable();
            $table->string('gender_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('image')->nullable();
            $table->integer('year_of_enter')->nullable();
            $table->string('academicDegree_code')->nullable();
            $table->string('academicDegree_name')->nullable();
            $table->string('academicRank_code')->nullable();
            $table->string('academicRank_name')->nullable();
            // $table->unsignedBigInteger('department_id');
            $table->string('login')->nullable();
            $table->string('uuid')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('user_type')->nullable();           
            $table->string('phone')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
