<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Jediný účet do administrace. Přístupové údaje se berou z env
 * (ADMIN_EMAIL / ADMIN_PASSWORD / ADMIN_NAME) — viz .env.example.
 * Seeder je idempotentní, heslo přepíše při každém spuštění.
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'vedouci@raion-ryu.cz')],
            [
                'name' => env('ADMIN_NAME', 'Vedoucí klubu'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'zmente-toto-heslo')),
                'email_verified_at' => now(),
            ],
        );
    }
}
