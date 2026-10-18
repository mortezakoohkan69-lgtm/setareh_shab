<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای دسته‌بندی');
            $table->string('title', 100)->index()->comment('عنوان دسته‌بندی');
            $table->enum('type', ['product', 'material'])->default('product')->index()->comment('نوع دسته‌بندی: محصول یا ماده اولیه');
            $table->boolean('is_active')->default(true)->index()->comment('وضعیت فعال بودن دسته‌بندی');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد دسته‌بندی');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش دسته‌بندی');
            $table->softDeletes()->comment('تاریخ حذف نرم دسته‌بندی');
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای کالا');
            $table->foreignId('category_id')->comment('شناسه دسته‌بندی')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 150)->index()->comment('نام محصول یا ماده اولیه');
            $table->string('sku', 50)->nullable()->unique()->comment('کد اختصاصی یا بارکد کالا');
            $table->decimal('cost_price', 15, 2)->default(0)->comment('قیمت خرید یا قیمت تمام شده');
            $table->decimal('sale_price', 15, 2)->default(0)->comment('قیمت فروش به مشتری');
            $table->enum('discount_type', ['none', 'percent', 'fixed'])->default('none')->comment('نوع تخفیف پیش‌فرض محصول');
            $table->decimal('discount_value', 15, 2)->default(0)->comment('مقدار تخفیف پیش‌فرض');
            $table->boolean('is_active')->default(true)->index()->comment('وضعیت فعال بودن کالا جهت فروش');
            $table->boolean('is_material')->default(false)->index()->comment('آیا این آیتم ماده اولیه است؟');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد کالا');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش کالا');
            $table->softDeletes()->comment('تاریخ حذف نرم کالا');
        });

        Schema::create('product_recipes', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای فرمول ساخت');
            $table->foreignId('product_id')->comment('شناسه محصول نهایی')->constrained('products')->cascadeOnDelete();
            $table->foreignId('material_id')->comment('شناسه ماده اولیه تشکیل‌دهنده')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 10, 3)->comment('مقدار مصرفی ماده اولیه در این محصول');
            $table->string('unit', 20)->comment('واحد سنجش مصرف مانند گرم، سی‌سی یا عدد');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد فرمول');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش فرمول');
            $table->unique(['product_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recipes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
