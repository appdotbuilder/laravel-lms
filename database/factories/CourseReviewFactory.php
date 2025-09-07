<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseReview>
 */
class CourseReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $studentIds = User::students()->pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();
        
        return [
            'student_id' => $studentIds ? fake()->randomElement($studentIds) : User::factory()->student(),
            'course_id' => $courseIds ? fake()->randomElement($courseIds) : Course::factory(),
            'rating' => fake()->numberBetween(3, 5), // Most reviews are positive
            'review' => fake()->optional(0.8)->paragraph(2),
            'approved' => fake()->boolean(95), // 95% approved
        ];
    }

    /**
     * Indicate that the review is not approved.
     */
    public function unapproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved' => false,
        ]);
    }

    /**
     * Indicate that the review is highly rated.
     */
    public function fiveStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
            'review' => fake()->randomElement([
                'Excellent course! I learned so much and the instructor explained everything clearly.',
                'This course exceeded my expectations. Great content and well-structured lessons.',
                'Amazing course! Perfect for beginners and the projects were very helpful.',
                'Outstanding instructor and course content. Highly recommend to anyone learning this topic.',
                'Best course I\'ve taken online. Clear explanations and practical examples.',
            ]),
        ]);
    }
}