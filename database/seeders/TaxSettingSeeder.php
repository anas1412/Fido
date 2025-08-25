<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxSetting;

class TaxSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default tax setting if none exists
        if (TaxSetting::count() === 0) {
            TaxSetting::create([
                'tva' => env('TAX_TVA', 0.19),
                'rs' => env('TAX_RS', 0.03),
                'tf' => env('TAX_TF', 1.00),
            ]);
        }
    }
}