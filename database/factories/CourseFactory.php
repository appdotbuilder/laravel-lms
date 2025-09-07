<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $instructorIds = User::instructors()->pluck('id')->toArray();
        $categoryIds = CourseCategory::active()->pluck('id')->toArray();
        
        return [
            'title' => rtrim($title, '.'),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->paragraph(),
            'slug' => Str::slug($title),
            'thumbnail' => fake()->imageUrl(640, 480, 'education'),
            'price' => fake()->randomFloat(2, 19.99, 299.99),
            'status' => fake()->randomElement(['draft', 'published', 'pending_approval']),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'duration_minutes' => fake()->numberBetween(60, 1200), // 1-20 hours
            'tags' => fake()->words(random_int(2, 5)),
            'instructor_id' => $instructorIds ? fake()->randomElement($instructorIds) : User::factory()->instructor(),
            'category_id' => $categoryIds ? fake()->randomElement($categoryIds) : CourseCategory::factory(),
            'max_students' => fake()->optional()->numberBetween(10, 500),
            'featured' => fake()->boolean(20), // 20% chance of being featured
            'rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(0, 500),
            'total_students' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the course is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the course is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the course is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the course is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0.00,
        ]);
    }
}