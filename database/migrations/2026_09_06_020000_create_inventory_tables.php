<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای موجودی');
            $table->foreignId('product_id')->unique()->comment('شناسه کالا یا ماده اولیه')->constrained('products')->cascadeOnDelete();
            $table->decimal('current_stock', 10, 3)->default(0)->comment('موجودی فعلی در انبار');
            $table->decimal('min_stock_alert', 10, 3)->default(0)->index()->comment('حداقل موجودی جهت هشدار کسر انبار');
            $table->string('unit', 20)->comment('واحد اصلی سنجش در انبار');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد رکورد موجودی');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین به‌روزرسانی موجودی');
        });

        Schema::create('inventory_logs', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای تراکنش انبار');
            $table->foreignId('product_id')->comment('شناسه محصول یا ماده اولیه')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['purchase', 'sale', 'waste', 'manual_adj'])->index()->comment('نوع تراکنش انبار: خرید، فروش، ضایعات یا اصلاح دستی');
            $table->decimal('quantity', 10, 3)->comment('مقدار تغییر یافته، مثبت برای ورودی و منفی برای خروجی');
            $table->unsignedBigInteger('reference_id')->nullable()->index()->comment('شناسه سند یا فاکتور مرتبط');
            $table->foreignId('user_id')->nullable()->comment('شناسه ثبت‌کننده تراکنش انبار')->constrained('users')->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات و علت تغییر موجودی');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد تراکنش');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش تراکنش');
            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('inventory');
    }
};
