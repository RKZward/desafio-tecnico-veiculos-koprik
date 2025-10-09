<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Pode sobrescrever com variáveis de ambiente, se quiser
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $senha = env('ADMIN_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'      => 'Admin',
                'password'  => Hash::make($senha),
                'is_admin'  => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
