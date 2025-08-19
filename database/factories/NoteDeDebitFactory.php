<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NoteDeDebit>
 */
class NoteDeDebitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentMonth = Carbon::now();
        $randomDayInCurrentMonth = $currentMonth->startOfMonth()->addDays(rand(0, $currentMonth->daysInMonth - 1));

        return [
            'client_id' => \App\Models\Client::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'description' => $this->faker->sentence,
            'date' => $randomDayInCurrentMonth,
            'created_at' => $randomDayInCurrentMonth,
            'updated_at' => $randomDayInCurrentMonth,
        ];
    }
}
