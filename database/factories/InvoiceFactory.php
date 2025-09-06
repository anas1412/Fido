<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\TaxSetting;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $totalHorsTaxe = $this->faker->randomFloat(2, 100, 5000);

        return [
            'client_id' => Client::factory(),
            'client_name' => '',
            'client_mf' => '',
            'invoice_number' => '',
            'date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'total_hors_taxe' => $totalHorsTaxe,
            'tva' => 0,
            'montant_ttc' => 0,
            'timbre_fiscal' => 0,
            'net_a_payer' => 0,
        ];
    }
}