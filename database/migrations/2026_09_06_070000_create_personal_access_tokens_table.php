<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->id()->comment('شناسه یکتای توکن دسترسی');
            $table->morphs('tokenable');
            $table->string('name')->comment('نام توکن');
            $table->string('token', 64)->unique()->comment('هش توکن دسترسی');
            $table->text('abilities')->nullable()->comment('سطوح دسترسی توکن');
            $table->timestamp('last_used_at')->nullable()->comment('آخرین زمان استفاده از توکن');
            $table->timestamp('expires_at')->nullable()->index()->comment('زمان انقضای توکن');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
