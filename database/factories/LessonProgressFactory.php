<?php

namespace Database\Factories;

use App\Models\CourseLesson;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonProgress>
 */
class LessonProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enrollmentIds = Enrollment::pluck('id')->toArray();
        $lessonIds = CourseLesson::pluck('id')->toArray();
        $completed = fake()->boolean(60); // 60% chance of completion
        
        return [
            'enrollment_id' => $enrollmentIds ? fake()->randomElement($enrollmentIds) : Enrollment::factory(),
            'lesson_id' => $lessonIds ? fake()->randomElement($lessonIds) : CourseLesson::factory(),
            'completed' => $completed,
            'watch_time_seconds' => $completed 
                ? fake()->numberBetween(300, 3600) // 5min to 1 hour for completed
                : fake()->numberBetween(0, 1800), // 0 to 30min for incomplete
            'completed_at' => $completed ? fake()->dateTimeBetween('-3 months', 'now') : null,
        ];
    }

    /**
     * Indicate that the lesson is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => true,
            'completed_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'watch_time_seconds' => fake()->numberBetween(300, 3600),
        ]);
    }

    /**
     * Indicate that the lesson is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => false,
            'completed_at' => null,
            'watch_time_seconds' => fake()->numberBetween(60, 1800),
        ]);
    }
}