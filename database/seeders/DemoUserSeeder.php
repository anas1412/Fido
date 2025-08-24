<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Honoraire;
use App\Models\Invoice;
use App\Models\NoteDeDebit;
use App\Models\TaxSetting;
use App\Models\InvoiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('DEMO_EMAIL', 'demo@fido.tn')],
            [
                'name' => env('DEMO_NAME', 'Admin'),
                'password' => Hash::make(env('DEMO_PASSWORD', 'password')),
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );

        $taxSettings = TaxSetting::firstOrCreate(
            [],
            [
                'tva' => 0.19,
                'rs' => 0.03,
                'tf' => 1,
            ]
        );

        $invoiceSequentialNumber = 0; // Initialize counter

        Client::factory(4)->create()->each(function ($client) use ($taxSettings, &$invoiceSequentialNumber) {
            Honoraire::factory(5)->create(function () use ($client, $taxSettings) {
                $montantHT = fake()->randomFloat(2, 1000, 10000);
                $tva = $montantHT * $taxSettings->tva;
                $rs = $montantHT * $taxSettings->rs;
                $tf = $taxSettings->tf;
                return [
                    'client_id' => $client->id,
                    'montantHT' => $montantHT,
                    'tva' => $tva,
                    'rs' => $rs,
                    'tf' => $tf,
                    'montantTTC' => $montantHT + $tva,
                    'netapayer' => ($montantHT + $tva) - $rs - $tf,
                ];
            });

            NoteDeDebit::factory(2)->create(['client_id' => $client->id]);

            Invoice::factory(2)->make()->each(function ($invoice) use ($client, $taxSettings, &$invoiceSequentialNumber) {
                $invoiceSequentialNumber++; // Increment counter
                $currentYear = date('Y');
                $invoiceNumber = str_pad($invoiceSequentialNumber, 4, '0', STR_PAD_LEFT) . $currentYear;

                $invoice->invoice_number = $invoiceNumber;
                $invoice->client_id = $client->id;

                // Introduce randomness for client_name and client_mf
                if (rand(0, 1)) { // 50% chance to use a different client's details
                    $randomClient = Client::inRandomOrder()->where('id', '!=', $client->id)->first();
                    if ($randomClient) {
                        $invoice->client_name = $randomClient->name;
                        $invoice->client_mf = $randomClient->mf;
                    } else {
                        // Fallback if no other client exists (e.g., only one client in DB)
                        $invoice->client_name = $client->name;
                        $invoice->client_mf = $client->mf;
                    }
                } else {
                    $invoice->client_name = $client->name;
                    $invoice->client_mf = $client->mf;
                }

                // Create invoice items first
                $invoiceItems = InvoiceItem::factory(rand(1, 5))->make();

                $totalHorsTaxe = $invoiceItems->sum('total_price');
                $tva = $totalHorsTaxe * $taxSettings->tva;
                $montantTTC = $totalHorsTaxe + $tva;
                $timbreFiscal = $taxSettings->tf;
                $netAPayer = $montantTTC + $timbreFiscal;

                $invoice->total_hors_taxe = $totalHorsTaxe;
                $invoice->tva = $tva;
                $invoice->montant_ttc = $montantTTC;
                $invoice->timbre_fiscal = $timbreFiscal;
                $invoice->net_a_payer = $netAPayer;

                $invoice->save(); // Save the invoice after calculations

                // Attach items to the saved invoice
                $invoiceItems->each(function ($item) use ($invoice) {
                    $item->invoice_id = $invoice->id;
                    $item->save();
                });
            });
        });
    }
}
