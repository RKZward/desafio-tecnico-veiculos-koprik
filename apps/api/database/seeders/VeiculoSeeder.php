<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Veiculo;
use App\Models\ImagemVeiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class VeiculoSeeder extends Seeder
{
    public function run(): void
    {
        // pega admin se existir, senão cria um user padrão para “dono”
        $owner = User::where('is_admin', true)->first() ?? User::first() ?? User::factory()->create([
            'name' => 'Demo Owner', 'email' => 'owner@example.com', 'password' => bcrypt('password'),
        ]);

        Veiculo::factory()
            ->count(10)
            ->state(fn() => ['user_id' => $owner->id])
            ->create()
            ->each(function (Veiculo $v) {
                // cria 2 “imagens” vazias por veículo e marca uma como capa
                for ($i=1; $i<=2; $i++) {
                    $path = "vehicles/{$v->id}/seed-{$i}.jpg";
                    Storage::disk('public')->put($path, 'seed'); // conteúdo simples
                    ImagemVeiculo::create([
                        'vehicle_id' => $v->id,
                        'path'       => $path,
                        'is_cover'   => $i === 1,
                    ]);
                }
            });
    }
}
