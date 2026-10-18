<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای حساب');
            $table->string('title', 100)->index()->comment('عنوان حساب یا دستگاه کارتخوان');
            $table->string('bank_name', 100)->index()->comment('نام بانک');
            $table->string('account_number', 50)->nullable()->comment('شماره حساب');
            $table->string('card_number', 16)->nullable()->comment('شماره کارت ۱۶ رقمی');
            $table->string('iban', 30)->nullable()->comment('شماره شبا');
            $table->decimal('initial_balance', 15, 2)->default(0)->comment('موجودی اولیه حساب');
            $table->decimal('current_balance', 15, 2)->default(0)->comment('موجودی فعلی حساب');
            $table->enum('account_type', ['main', 'sub'])->default('main')->index()->comment('نوع حساب: اصلی یا فرعی');
            $table->boolean('is_active')->default(true)->index()->comment('وضعیت فعال بودن حساب');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ایجاد حساب');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش حساب');
            $table->softDeletes()->comment('تاریخ حذف نرم حساب برای حفظ سوابق مالی');
        });

        Schema::create('cheques', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای چک');
            $table->enum('type', ['received', 'paid'])->index()->comment('نوع چک: دریافتی یا پرداختی');
            $table->string('serial_number', 50)->index()->comment('شماره سریال یا صیادی چک');
            $table->decimal('amount', 15, 2)->comment('مبلغ چک');
            $table->string('payee_payor', 150)->comment('نام صادرکننده یا دریافت‌کننده چک');
            $table->string('for_reason', 255)->nullable()->comment('بابت یا بابت فاکتور');
            $table->date('issue_date')->index()->comment('تاریخ صدور چک');
            $table->date('due_date')->index()->comment('تاریخ سررسید چک');
            $table->enum('status', ['pending', 'cleared', 'bounced'])->default('pending')->index()->comment('وضعیت چک: در جریان، پاس شده یا برگشتی');
            $table->foreignId('bank_account_id')->nullable()->comment('حساب بانکی متصل جهت واریز یا برداشت')->constrained('bank_accounts')->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات تکمیلی چک');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت چک');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش چک');
            $table->softDeletes()->comment('تاریخ حذف نرم چک برای حفظ سوابق مالی');
            $table->index(['status', 'due_date']);
        });

        Schema::create('expenses', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای هزینه');
            $table->string('title', 150)->comment('عنوان هزینه');
            $table->decimal('amount', 15, 2)->comment('مبلغ هزینه');
            $table->foreignId('bank_account_id')->nullable()->comment('حساب بانکی پرداخت‌کننده')->constrained('bank_accounts')->nullOnDelete();
            $table->date('expense_date')->index()->comment('تاریخ وقوع هزینه');
            $table->foreignId('user_id')->nullable()->comment('شناسه کاربر ثبت‌کننده هزینه')->constrained('users')->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات بابت هزینه');
            $table->timestamp('created_at')->nullable()->comment('تاریخ ثبت هزینه');
            $table->timestamp('updated_at')->nullable()->comment('تاریخ آخرین ویرایش هزینه');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('cheques');
        Schema::dropIfExists('bank_accounts');
    }
};
