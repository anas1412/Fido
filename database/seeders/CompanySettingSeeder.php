<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default company setting if none exists
        if (CompanySetting::count() === 0) {
            CompanySetting::create([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }
    }
}