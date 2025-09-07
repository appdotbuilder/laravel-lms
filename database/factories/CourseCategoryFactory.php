<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseCategory>
 */
class CourseCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
            'icon' => fake()->randomElement(['code', 'design', 'business', 'marketing', 'music', 'photography']),
            'sort_order' => fake()->numberBetween(1, 100),
            'active' => fake()->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the category is for programming.
     */
    public function programming(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Programming',
            'slug' => 'programming',
            'description' => 'Learn to code and build applications',
            'color' => '#3B82F6',
            'icon' => 'code',
        ]);
    }

    /**
     * Indicate that the category is for design.
     */
    public function design(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Design',
            'slug' => 'design',
            'description' => 'Master the art of visual design',
            'color' => '#F59E0B',
            'icon' => 'design',
        ]);
    }
}