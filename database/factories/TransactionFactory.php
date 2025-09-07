<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();
        
        return [
            'transaction_id' => 'TXN_' . fake()->unique()->regexify('[A-Z0-9]{10}'),
            'user_id' => $userIds ? fake()->randomElement($userIds) : User::factory(),
            'course_id' => fake()->optional(0.8)->randomElement($courseIds ?: [Course::factory()]),
            'type' => fake()->randomElement(['purchase', 'refund', 'payout']),
            'amount' => fake()->randomFloat(2, 19.99, 299.99),
            'currency' => 'USD',
            'status' => fake()->randomElement(['pending', 'completed', 'failed']),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'stripe', 'bank_transfer']),
            'gateway' => fake()->randomElement(['stripe', 'paypal', 'square']),
            'gateway_transaction_id' => fake()->regexify('[a-z0-9]{20}'),
            'metadata' => fake()->optional()->randomElement([
                ['card_type' => 'visa', 'last_four' => fake()->numerify('####')],
                ['paypal_email' => fake()->email()],
                ['bank_account' => fake()->numerify('****####')],
            ]),
        ];
    }

    /**
     * Indicate that the transaction is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the transaction is a purchase.
     */
    public function purchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'purchase',
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the transaction is a payout.
     */
    public function payout(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'payout',
            'course_id' => null,
        ]);
    }
}