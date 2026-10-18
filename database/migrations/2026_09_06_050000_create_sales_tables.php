<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای فاکتور خرید');
            $table->string('supplier_name', 150)->comment('نام تامین‌کننده یا فروشنده');
            $table->foreignId('bank_account_id')->nullable()->comment('حساب بانکی پرداختی')->constrained('bank_accounts')->nullOnDelete();
            $table->decimal('total_amount', 15, 2)->comment('مبلغ کل فاکتور خرید');
            $table->timestamp('purchase_date')->index()->comment('تاریخ و زمان انجام خرید');
            $table->foreignId('user_id')->comment('شناسه کاربر ثبت‌کننده خرید')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت فاکتور خرید');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش فاکتور خرید');
            $table->index(['purchase_date', 'user_id']);
        });

        Schema::create('purchase_items', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای قلم خرید');
            $table->foreignId('purchase_id')->comment('شناسه فاکتور خرید')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('product_id')->comment('شناسه کالا یا ماده اولیه خریداری‌شده')->constrained('products')->restrictOnDelete();
            $table->decimal('quantity', 10, 2)->comment('تعداد یا مقدار خریداری شده');
            $table->decimal('unit_cost', 15, 2)->comment('قیمت فی خرید');
            $table->decimal('total_cost', 15, 2)->comment('قیمت کل این قلم در فاکتور');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت قلم خرید');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش قلم خرید');
            $table->index(['purchase_id', 'product_id']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای فاکتور فروش');
            $table->foreignId('customer_id')->nullable()->comment('شناسه مشتری در صورت وجود')->constrained('customers')->nullOnDelete();
            $table->foreignId('bank_account_id')->nullable()->comment('دستگاه کارتخوان یا حساب دریافت‌کننده')->constrained('bank_accounts')->nullOnDelete();
            $table->foreignId('applied_discount_id')->nullable()->comment('شناسه طرح تخفیف اعمال‌شده')->constrained('discounts')->nullOnDelete();
            $table->decimal('total_raw_amount', 15, 2)->comment('مبلغ کل ناخالص فاکتور قبل از تخفیف');
            $table->decimal('total_discount', 15, 2)->default(0)->comment('مجموع تخفیف اعمال‌شده روی فاکتور');
            $table->decimal('final_amount', 15, 2)->comment('مبلغ قابل پرداخت نهایی مشتری');
            $table->decimal('total_cost_price', 15, 2)->comment('مجموع بهای تمام شده فاکتور جهت محاسبه سود');
            $table->enum('payment_type', ['card', 'cash', 'mixed'])->default('card')->index()->comment('روش پرداخت: کارتخوان، نقد یا ترکیبی');
            $table->enum('status', ['completed', 'cancelled'])->default('completed')->index()->comment('وضعیت فاکتور فروش');
            $table->foreignId('user_id')->comment('شناسه صندوق‌دار یا کاربر ثبت‌کننده')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت فاکتور فروش');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش فاکتور فروش');
            $table->index(['status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای قلم فروش');
            $table->foreignId('order_id')->comment('شناسه فاکتور فروش')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->comment('شناسه محصول فروخته‌شده')->constrained('products')->restrictOnDelete();
            $table->integer('quantity')->comment('تعداد فروخته‌شده');
            $table->decimal('unit_cost_price', 15, 2)->comment('قیمت تمام‌شده یک واحد در لحظه فروش');
            $table->decimal('unit_sale_price', 15, 2)->comment('قیمت فروش یک واحد به مشتری');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('تخفیف تخصیص‌یافته به این قلم');
            $table->decimal('final_price', 15, 2)->comment('مبلغ نهایی این قلم پس از کسر تخفیف');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت قلم فروش');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش قلم فروش');
            $table->index(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};
