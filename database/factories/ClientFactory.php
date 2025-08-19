<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $previousMonth = Carbon::now()->subMonth();
        $randomDayInPreviousMonth = $previousMonth->startOfMonth()->addDays(rand(0, $previousMonth->daysInMonth - 1));

        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'mf' => $this->faker->unique()->numerify('#######/#/#/###/####'),
            'created_at' => $randomDayInPreviousMonth,
            'updated_at' => $randomDayInPreviousMonth,
        ];
    }
}
