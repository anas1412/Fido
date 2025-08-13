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
                'tva' => 0.19,
                'rs' => 0.0,
                'tf' => 0.0,
            ]);
        }
    }
}