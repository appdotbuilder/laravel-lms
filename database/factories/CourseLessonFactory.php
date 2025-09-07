<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseLesson>
 */
class CourseLessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseIds = Course::pluck('id')->toArray();
        
        return [
            'title' => fake()->sentence(4),
            'content' => fake()->optional()->paragraphs(2, true),
            'video_url' => fake()->optional(0.8)->url(),
            'duration_minutes' => fake()->numberBetween(5, 60),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_free' => fake()->boolean(10), // 10% chance of being free
            'type' => fake()->randomElement(['video', 'text', 'quiz', 'assignment']),
            'resources' => fake()->optional()->randomElement([
                ['pdf' => fake()->url(), 'slides' => fake()->url()],
                ['code' => fake()->url()],
            ]),
            'course_id' => $courseIds ? fake()->randomElement($courseIds) : Course::factory(),
        ];
    }

    /**
     * Indicate that the lesson is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free' => true,
        ]);
    }

    /**
     * Indicate that the lesson is a video lesson.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'video',
            'video_url' => fake()->url(),
            'duration_minutes' => fake()->numberBetween(10, 45),
        ]);
    }

    /**
     * Indicate that the lesson is a quiz.
     */
    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz',
            'video_url' => null,
            'duration_minutes' => fake()->numberBetween(5, 15),
        ]);
    }
}