<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'age' => $this->faker->numberBetween(20, 40),
            'start_time' => $this->faker->dateTime(),
            'end_time' => $this->faker->dateTime(),
            'start_at' => $this->faker->date(),
            'end_at' => $this->faker->date(),
            'baby_number' => 0,
            'adult_number' => $this->faker->numberBetween(1, 3),
            'baby_price' => $this->faker->randomFloat(2, 1, 100),
            'adult_price' => $this->faker->randomFloat(2, 1, 100),
            'tax_price' => $this->faker->randomFloat(2, 1, 30),
            'total_price' => $this->faker->randomFloat(2, 1, 100),
            'status' => 'testing',
            'user_id' => UserFactory::new()->create()->id,
        ];
    }
}
