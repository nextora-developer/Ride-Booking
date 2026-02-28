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
        // Driver Day
        User::updateOrCreate(
            ['email' => 'driver_day@driver.com'],
            [
                'name'          => 'Driver Day',
                'full_name'     => 'Ahmad Faiz',
                'phone'         => '0123456789',
                'car_plate'     => 'VCD1234',
                'car_model'     => 'Perodua Bezza 1.3',
                'bank_name'     => 'Maybank',
                'bank_account'  => '123456789012',
                'password'      => Hash::make('password'),
                'role'          => 'driver',
                'shift'         => 'day',
                'driver_status' => 'approved',
                'is_online'     => 1,
            ]
        );

        // Driver Night
        User::updateOrCreate(
            ['email' => 'driver_night@driver.com'],
            [
                'name'          => 'Driver Night',
                'full_name'     => 'Siva Kumar',
                'phone'         => '0198765432',
                'car_plate'     => 'WXY5678',
                'car_model'     => 'Toyota Vios 1.5',
                'bank_name'     => 'CIMB Bank',
                'bank_account'  => '987654321098',
                'password'      => Hash::make('password'),
                'role'          => 'driver',
                'shift'         => 'night',
                'driver_status' => 'approved',
                'is_online'     => 1,
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
                'full_name' => 'Sia Kai Fong',
                'phone' => '0167219945',
                'password' => Hash::make('password'),
                'role' => 'user',
                'shift' => null,
                'driver_status' => null,
            ]
        );
    }
}
