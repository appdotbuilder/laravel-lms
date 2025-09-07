<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['admin', 'instructor', 'student']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'bio' => fake()->optional()->paragraph(),
            'profile_photo' => fake()->optional()->imageUrl(200, 200, 'people'),
            'total_earnings' => fake()->randomFloat(2, 0, 10000),
            'is_approved' => fake()->boolean(95), // 95% approved
            'preferences' => fake()->optional()->randomElement([
                ['theme' => 'light', 'notifications' => true],
                ['theme' => 'dark', 'notifications' => false],
            ]),
            'last_login_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the user is an instructor.
     */
    public function instructor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'instructor',
            'bio' => fake()->paragraph(),
            'total_earnings' => fake()->randomFloat(2, 100, 50000),
        ]);
    }

    /**
     * Indicate that the user is a student.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'student',
            'total_earnings' => 0,
        ]);
    }
}
