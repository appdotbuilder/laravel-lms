<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['course_approval', 'forum_reply', 'payout_request', 'course_review', 'enrollment'];
        $type = fake()->randomElement($types);
        
        return [
            'user_id' => User::factory(),
            'type' => $type,
            'notifiable_type' => Course::class,
            'notifiable_id' => Course::factory(),
            'title' => $this->getTitleForType($type),
            'message' => fake()->sentence(),
            'data' => [
                'additional_info' => fake()->words(3, true),
                'timestamp' => fake()->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            ],
            'read' => fake()->boolean(30), // 30% chance of being read
        ];
    }

    /**
     * Indicate that the notification is unread.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read' => false,
        ]);
    }

    /**
     * Indicate that the notification is read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read' => true,
        ]);
    }

    /**
     * Create a course approval notification.
     */
    public function courseApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'course_approval',
            'title' => 'Course Approval Required',
            'message' => 'A new course has been submitted for approval.',
        ]);
    }

    /**
     * Create a forum reply notification.
     */
    public function forumReply(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'forum_reply',
            'title' => 'New Forum Reply',
            'message' => 'Someone replied to your forum post.',
        ]);
    }

    /**
     * Create a payout request notification.
     */
    public function payoutRequest(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'payout_request',
            'title' => 'Payout Request',
            'message' => 'An instructor has requested a payout.',
        ]);
    }

    /**
     * Get title based on notification type.
     *
     * @param  string  $type
     * @return string
     */
    protected function getTitleForType(string $type): string
    {
        return match ($type) {
            'course_approval' => 'Course Approval Required',
            'forum_reply' => 'New Forum Reply',
            'payout_request' => 'Payout Request',
            'course_review' => 'New Course Review',
            'enrollment' => 'New Student Enrollment',
            default => 'Notification',
        };
    }
}