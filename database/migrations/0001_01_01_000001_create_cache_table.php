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
        Schema::create('cache', function (Blueprint $table): void {
            $table->string('key')->primary()->comment('کلید یکتای داده کش');
            $table->mediumText('value')->comment('مقدار ذخیره‌شده در کش');
            $table->bigInteger('expiration')->index()->comment('زمان انقضای داده به صورت Unix Timestamp');
        });

        Schema::create('cache_locks', function (Blueprint $table): void {
            $table->string('key')->primary()->comment('کلید قفل کش');
            $table->string('owner')->comment('شناسه مالک قفل');
            $table->bigInteger('expiration')->index()->comment('زمان انقضای قفل به صورت Unix Timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
