<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();

            // 顾客（user role:user）
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();

            // 操作人（manager）
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();

            // 变动记录
            $table->decimal('before', 10, 2)->default(0);
            $table->decimal('change', 10, 2)->default(0);
            $table->decimal('after', 10, 2)->default(0);

            $table->string('action', 30)->default('update');
            $table->string('note')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_logs');
    }
};
