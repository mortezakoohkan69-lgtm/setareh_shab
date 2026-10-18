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
        Schema::create('jobs', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای کار صف');
            $table->string('queue')->index()->comment('نام صف پردازش کار');
            $table->longText('payload')->comment('داده کامل کار صف');
            $table->unsignedSmallInteger('attempts')->comment('تعداد دفعات تلاش پردازش');
            $table->unsignedInteger('reserved_at')->nullable()->comment('زمان رزرو کار به صورت Unix Timestamp');
            $table->unsignedInteger('available_at')->comment('زمان آماده شدن کار به صورت Unix Timestamp');
            $table->unsignedInteger('created_at')->comment('زمان ایجاد کار به صورت Unix Timestamp');
        });

        Schema::create('job_batches', function (Blueprint $table): void {
            $table->string('id')->primary()->comment('شناسه یکتای دسته کارها');
            $table->string('name')->comment('نام دسته کارها');
            $table->integer('total_jobs')->comment('تعداد کل کارهای دسته');
            $table->integer('pending_jobs')->comment('تعداد کارهای در انتظار');
            $table->integer('failed_jobs')->comment('تعداد کارهای ناموفق');
            $table->longText('failed_job_ids')->comment('شناسه کارهای ناموفق');
            $table->mediumText('options')->nullable()->comment('تنظیمات دسته کارها');
            $table->integer('cancelled_at')->nullable()->comment('زمان لغو دسته به صورت Unix Timestamp');
            $table->integer('created_at')->comment('زمان ایجاد دسته به صورت Unix Timestamp');
            $table->integer('finished_at')->nullable()->comment('زمان پایان دسته به صورت Unix Timestamp');
        });

        Schema::create('failed_jobs', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای کار ناموفق');
            $table->string('uuid')->unique()->comment('شناسه یکتای عمومی کار');
            $table->string('connection')->comment('نام اتصال صف');
            $table->string('queue')->comment('نام صف کار');
            $table->longText('payload')->comment('داده کامل کار ناموفق');
            $table->longText('exception')->comment('جزئیات خطای پردازش');
            $table->timestamp('failed_at')->useCurrent()->comment('زمان شکست پردازش کار');

            $table->index(['connection', 'queue', 'failed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
