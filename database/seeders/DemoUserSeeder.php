<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Honoraire;
use App\Models\NoteDeDebit;
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

        Client::factory(10)->create()->each(function ($client) {
            Honoraire::factory(5)->create(['client_id' => $client->id]);
            NoteDeDebit::factory(2)->create(['client_id' => $client->id]);
        });
    }
}
