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
            $table->string('name'); // الاسم
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // كلمة المرور
            $table->enum('role', ['patient', 'doctor'])->default('patient');
            $table->string('phone_number')->nullable(); // رقم الجوال
            $table->string('address')->nullable(); // العنوان
            $table->string('national_id')->unique(); // رقم الهوية هان
            $table->string('identity_image')->nullable(); // صورة الهوية
            $table->string('health_insurance_number')->nullable(); // رقم التأمين الصحي
            $table->string('specialty')->nullable(); // التخصص (للطبيب بس)
            $table->integer('age')->nullable(); // العمر
            $table->enum('gender', ['male', 'female'])->nullable(); // النوع
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
