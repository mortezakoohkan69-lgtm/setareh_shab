<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای کاربر');
            $table->string('name', 100)->comment('نام و نام خانوادگی');
            $table->string('username', 50)->unique()->comment('نام کاربری');
            $table->string('email', 150)->nullable()->unique()->comment('آدرس ایمیل');
            $table->string('mobile', 15)->unique()->comment('شماره موبایل کاربر');
            $table->timestamp('email_verified_at')->nullable()->comment('تاریخ تایید ایمیل');
            $table->timestamp('mobile_verified_at')->nullable()->comment('تاریخ تایید موبایل');
            $table->string('password')->comment('رمز عبور هش شده');
            $table->string('two_factor_secret')->nullable()->comment('کد محرمانه ورود دو مرحله‌ای');
            $table->text('two_factor_recovery_codes')->nullable()->comment('کدهای بازیابی ورود دو مرحله‌ای');
            $table->boolean('is_active')->default(true)->index()->comment('وضعیت فعال یا غیرفعال بودن حساب');
            $table->rememberToken()->comment('توکن به یاد داشتن ورود');
            $table->timestamp('last_login_at')->nullable()->comment('تاریخ و زمان آخرین ورود');
            $table->string('last_login_ip', 45)->nullable()->comment('IP آخرین ورود کاربر');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد حساب');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش حساب');
            $table->softDeletes()->comment('تاریخ حذف نرم برای حفظ سوابق کاربر');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('mobile')->primary()->comment('شماره موبایل کاربر جهت بازیابی');
            $table->string('token')->comment('توکن یکبار مصرف بازیابی رمز عبور');
            $table->string('ip_address', 45)->nullable()->comment('IP درخواست‌کننده بازیابی رمز');
            $table->timestamp('created_at')->nullable()->comment('زمان ارسال کد بازیابی');
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary()->comment('شناسه نشست کاربر');
            $table->foreignId('user_id')->nullable()->index()->comment('شناسه کاربر متصل')->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable()->comment('آدرس IP کاربر');
            $table->text('user_agent')->nullable()->comment('مشخصات مرورگر و سیستم‌عامل کاربر');
            $table->longText('payload')->comment('داده‌های ذخیره‌شده نشست');
            $table->integer('last_activity')->index()->comment('زمان آخرین فعالیت به صورت Unix Timestamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
