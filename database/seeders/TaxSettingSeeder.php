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
                'tva' => env('DEFAULT_TVA', 0.19),
                'rs' => env('DEFAULT_RS', 0.03),
                'tf' => env('DEFAULT_TF', 1.00),
            ]);
        }
    }
}