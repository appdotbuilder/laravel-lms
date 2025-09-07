<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
                'bio' => 'Platform administrator with full access to system management.',
                'preferences' => ['theme' => 'light', 'notifications' => true],
            ]
        );

        // Create instructor users
        User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Sarah Johnson',
                'role' => 'instructor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
                'bio' => 'Full-stack developer with 8+ years of experience teaching React and modern web development.',
                'total_earnings' => 8500.00,
                'preferences' => ['theme' => 'dark', 'notifications' => true],
            ]
        );

        User::firstOrCreate(
            ['email' => 'instructor2@example.com'],
            [
                'name' => 'Mike Chen',
                'role' => 'instructor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
                'bio' => 'Python expert and machine learning specialist with a passion for teaching beginners.',
                'total_earnings' => 12500.00,
                'preferences' => ['theme' => 'light', 'notifications' => false],
            ]
        );

        // Create student users
        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'John Doe',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
                'bio' => 'Aspiring web developer eager to learn new technologies.',
                'total_earnings' => 0.00,
                'preferences' => ['theme' => 'light', 'notifications' => true],
                'last_login_at' => now()->subHours(2),
            ]
        );

        User::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'name' => 'Emma Davis',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_approved' => true,
                'bio' => 'UX designer looking to expand skills in frontend development.',
                'total_earnings' => 0.00,
                'preferences' => ['theme' => 'dark', 'notifications' => true],
                'last_login_at' => now()->subDays(1),
            ]
        );

        // Create additional sample users
        User::factory()->admin()->count(2)->create();
        User::factory()->instructor()->count(5)->create();
        User::factory()->student()->count(10)->create();
    }
}