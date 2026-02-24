<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // admin/manager/driver/user
            $table->string('shift')->nullable()->after('role');      // day/night (manager & driver)
            $table->string('driver_status')->nullable()->after('shift'); // pending_approval/approved/...
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
