<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('full_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('ic_number')->nullable()->after('email');
            $table->string('car_plate')->nullable()->after('ic_number');
            $table->string('car_model')->nullable()->after('car_plate');
            $table->string('bank_name')->nullable()->after('car_model');
            $table->string('bank_account')->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
