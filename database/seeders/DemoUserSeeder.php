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
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@fido.tn',
            'password' => Hash::make('password'),
            'is_demo' => true,
        ]);

        Client::factory(10)->create()->each(function ($client) {
            Honoraire::factory(5)->create(['client_id' => $client->id]);
            NoteDeDebit::factory(2)->create(['client_id' => $client->id]);
        });
    }
}
