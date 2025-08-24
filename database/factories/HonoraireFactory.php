<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\TaxSetting;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Honoraire>
 */
class HonoraireFactory extends Factory
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

        $montantHT = $this->faker->randomFloat(2, 1000, 10000);

        return [
            'object' => $this->faker->sentence,
            'date' => $randomDayInCurrentMonth,
            'montantHT' => $montantHT,
            'tva' => 0,
            'rs' => 0,
            'tf' => 0,
            'montantTTC' => 0,
            'netapayer' => 0,
            'client_id' => \App\Models\Client::factory(),
            'exonere_tf' => $this->faker->boolean,
            'exonere_rs' => $this->faker->boolean,
            'exonere_tva' => $this->faker->boolean,
            'created_at' => $randomDayInCurrentMonth,
            'updated_at' => $randomDayInCurrentMonth,
        ];
    }
}
