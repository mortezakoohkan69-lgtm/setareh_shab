<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای مشتری');
            $table->string('first_name', 100)->comment('نام مشتری');
            $table->string('last_name', 100)->comment('نام خانوادگی مشتری');
            $table->string('mobile', 15)->unique()->index()->comment('شماره همراه مشتری');
            $table->date('birth_date')->nullable()->index()->comment('تاریخ تولد مشتری جهت تخفیف تولد');
            $table->integer('purchase_count')->default(0)->comment('تعداد کل خریدهای مشتری');
            $table->decimal('total_spent', 15, 2)->default(0)->comment('مجموع مبلغ خریدهای انجام‌شده');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد پرونده مشتری');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش پرونده مشتری');
            $table->softDeletes()->comment('تاریخ حذف نرم برای حفظ سوابق مشتری');
        });

        Schema::create('discounts', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای طرح تخفیف');
            $table->string('title', 150)->index()->comment('عنوان طرح تخفیف');
            $table->enum('type', ['birthday', 'purchase_count', 'happy_hour', 'event', 'product_specific'])->index()->comment('نوع تخفیف: تولد، تعداد خرید، ساعات خاص یا رویداد');
            $table->enum('discount_type', ['percent', 'fixed_amount'])->comment('نوع محاسبه تخفیف: درصدی یا مبلغ ثابت');
            $table->decimal('discount_value', 15, 2)->comment('مقدار درصد یا مبلغ تخفیف');
            $table->integer('min_purchases')->nullable()->comment('حداقل تعداد خرید برای فعال‌سازی');
            $table->dateTime('start_date')->nullable()->index()->comment('تاریخ و زمان شروع طرح تخفیف');
            $table->dateTime('end_date')->nullable()->index()->comment('تاریخ و زمان پایان طرح تخفیف');
            $table->time('start_time')->nullable()->comment('ساعت شروع روزانه برای ساعات شادی');
            $table->time('end_time')->nullable()->comment('ساعت پایان روزانه برای ساعات شادی');
            $table->string('days_of_week', 50)->nullable()->comment('روزهای فعال هفته، مانند 6,7');
            $table->boolean('is_active')->default(true)->index()->comment('وضعیت فعال بودن طرح');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد طرح تخفیف');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش طرح تخفیف');
            $table->index(['type', 'is_active']);
        });

        Schema::create('discount_products', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای تخصیص تخفیف');
            $table->foreignId('discount_id')->comment('شناسه تخفیف')->constrained('discounts')->cascadeOnDelete();
            $table->foreignId('product_id')->comment('شناسه محصول مشمول تخفیف')->constrained('products')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable()->comment('تاریخ تخصیص تخفیف');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش تخصیص');
            $table->unique(['discount_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_products');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('customers');
    }
};
