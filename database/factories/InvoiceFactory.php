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
        // Create a client for this invoice
        $client = Client::factory()->create(); // This will create a new client

        $totalHorsTaxe = $this->faker->randomFloat(2, 100, 5000);

        return [
            'client_id' => $client->id, // Use the ID of the newly created client
            'client_name' => $client->name, // Use the name of the newly created client
            'client_mf' => $client->mf, // Use the mf of the newly created client
            'client_address' => $client->address, // Use the address of the newly created client
            'invoice_number' => $this->faker->unique()->randomNumber(5),
            'date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'total_hors_taxe' => $totalHorsTaxe,
            'tva' => 0,
            'montant_ttc' => 0,
            'timbre_fiscal' => 0,
            'net_a_payer' => 0,
        ];
    }
}