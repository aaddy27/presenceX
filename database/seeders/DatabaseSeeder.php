<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin
        User::updateOrCreate(
            ['email' => 'admin@presencex.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
            ]
        );

        // Kiosk User for Tablet App
        User::updateOrCreate(
            ['email' => 'kiosk@presencex.com'],
            [
                'name' => 'Entrance Kiosk',
                'password' => Hash::make('kiosk123'),
                'role' => 'Kiosk',
            ]
        );

        // Default Shift
        Shift::updateOrCreate(
            ['shift_name' => 'General Shift'],
            [
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'grace_time_minutes' => 15,
                'half_day_minutes_threshold' => 240, // 4 hours
            ]
        );
    }
}
