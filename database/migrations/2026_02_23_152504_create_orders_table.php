<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // customer
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // assignment
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();

            // booking info
            $table->string('service_type', 40); // pickup_dropoff/charter/designated_driver/purchase/big_car/driver_only
            $table->string('pickup', 255);
            $table->string('dropoff', 255);
            $table->text('note')->nullable();

            // scheduling
            $table->string('schedule_type', 20)->default('now'); // now/scheduled
            $table->dateTime('scheduled_at')->nullable();

            // system shift
            $table->string('shift', 10); // day/night

            // status flow
            $table->string('status', 30)->default('pending'); // pending/assigned/on_the_way/arrived/completed/cancelled
            $table->dateTime('assigned_at')->nullable();

            // payment (manager assign later)
            $table->string('payment_type', 20)->nullable();   // cash/credit/transfer
            $table->string('payment_status', 30)->nullable(); // paid/on_account/pending_transfer

            $table->timestamps();

            $table->index(['shift', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
