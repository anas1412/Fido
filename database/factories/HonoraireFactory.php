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

        // Fetch tax settings from the database
        $taxSettings = TaxSetting::firstOrCreate(
            [],
            [
                'tva' => 0.19,
                'rs' => 0.03,
                'tf' => 1,
            ]
        );

        $montantHT = $this->faker->randomFloat(2, 1000, 10000);

        // Use tax settings for calculations
        $tva = $montantHT * $taxSettings->tva;
        $rs = $montantHT * $taxSettings->rs;
        $tf = $taxSettings->tf;

        return [
            'object' => $this->faker->sentence,
            'date' => $randomDayInCurrentMonth,
            'montantHT' => $montantHT,
            'tva' => $tva,
            'rs' => $rs,
            'tf' => $tf,
            'montantTTC' => $montantHT + $tva,
            'netapayer' => ($montantHT + $tva) - $rs - $tf,
            'client_id' => \App\Models\Client::factory(),
            'exonere_tf' => $this->faker->boolean,
            'exonere_rs' => $this->faker->boolean,
            'exonere_tva' => $this->faker->boolean,
            'created_at' => $randomDayInCurrentMonth,
            'updated_at' => $randomDayInCurrentMonth,
        ];
    }
}
