<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Boss',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'shift' => null,
                'driver_status' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Managers
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'manager_day@manager.com'],
            [
                'name' => 'Manager Day',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'shift' => 'day',
                'driver_status' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager_night@manager.com'],
            [
                'name' => 'Manager Night',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'shift' => 'night',
                'driver_status' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */
        // Approved Driver
        User::updateOrCreate(
            ['email' => 'driver_day@driver.com'],
            [
                'name' => 'Driver Day',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'shift' => 'day',
                'driver_status' => 'approved',
            ]
        );

        // Pending Driver
        User::updateOrCreate(
            ['email' => 'driver_night@driver.com'],
            [
                'name' => 'Driver Night',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'shift' => 'night',
                'driver_status' => 'pending_approval',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'shift' => null,
                'driver_status' => null,
            ]
        );
    }
}
