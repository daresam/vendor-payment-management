<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'corporate_id' => 1,
            'vendor_id' => 1,
            'invoice_number' => 'INV-' . $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'status' => $this->faker->randomElement(['OPEN', 'CLOSED']),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'description' => $this->faker->sentence(4),
        ];
    }
}

