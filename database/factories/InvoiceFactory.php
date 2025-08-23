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
        $currentYear = date('Y');
        $count = \App\Models\Invoice::count() + 1;
        $newInvoiceNumber = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;

        $client = Client::factory()->create();
        $totalHorsTaxe = $this->faker->randomFloat(2, 100, 5000);
        $taxSettings = TaxSetting::first() ?? TaxSetting::factory()->create();

        $tva = $totalHorsTaxe * $taxSettings->tva;
        $montantTTC = $totalHorsTaxe + $tva;
        $timbreFiscal = $taxSettings->tf;
        $netAPayer = $montantTTC + $timbreFiscal;

        return [
            'client_id' => $client->id,
            'client_name' => $client->name,
            'client_mf' => $client->mf,
            'invoice_number' => $this->faker->unique()->numerify(date('Y') . '########'),
            'date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'total_hors_taxe' => $totalHorsTaxe,
            'tva' => $tva,
            'montant_ttc' => $montantTTC,
            'timbre_fiscal' => $timbreFiscal,
            'net_a_payer' => $netAPayer,
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid', 'overdue']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            InvoiceItem::factory(rand(1, 5))->create(['invoice_id' => $invoice->id]);
        });
    }
}