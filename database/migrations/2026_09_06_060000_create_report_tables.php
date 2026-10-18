<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_sales_reports', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای گزارش روزانه');
            $table->date('report_date')->unique()->index()->comment('تاریخ روز گزارش');
            $table->integer('total_orders')->default(0)->comment('تعداد کل فاکتورهای فروش روز');
            $table->decimal('total_gross_sales', 15, 2)->default(0)->comment('مجموع فروش ناخالص روز');
            $table->decimal('total_discounts', 15, 2)->default(0)->comment('مجموع تخفیفات پرداختی روز');
            $table->decimal('total_net_sales', 15, 2)->default(0)->comment('مجموع فروش خالص روز');
            $table->decimal('total_cogs', 15, 2)->default(0)->comment('مجموع بهای تمام شده کالاهای فروخته‌شده روز');
            $table->decimal('total_expenses', 15, 2)->default(0)->comment('مجموع هزینه‌های جاری ثبت‌شده در روز');
            $table->decimal('gross_profit', 15, 2)->default(0)->comment('سود ناخالص روزانه');
            $table->decimal('net_profit', 15, 2)->default(0)->comment('سود خالص روزانه پس از کسر هزینه‌ها');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد گزارش');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین به‌روزرسانی گزارش');
        });

        Schema::create('product_sales_stats', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای آمار فروش محصول');
            $table->foreignId('product_id')->comment('شناسه محصول')->constrained('products')->cascadeOnDelete();
            $table->string('report_period', 7)->index()->comment('دوره گزارش‌گیری مانند 2026-09');
            $table->decimal('total_quantity_sold', 10, 2)->default(0)->comment('مجموع تعداد یا مقدار فروخته‌شده در این دوره');
            $table->decimal('total_revenue', 15, 2)->default(0)->comment('مجموع درآمد حاصل از این کالا');
            $table->decimal('total_profit', 15, 2)->default(0)->comment('مجموع سود حاصل از این کالا');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد آمار');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین به‌روزرسانی آمار');
            $table->unique(['product_id', 'report_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sales_stats');
        Schema::dropIfExists('daily_sales_reports');
    }
};
