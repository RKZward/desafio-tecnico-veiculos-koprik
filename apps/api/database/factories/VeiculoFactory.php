<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VeiculoFactory extends Factory
{
    protected $model = Veiculo::class;

    private function placaMercosul(): string
    {
        // Padrão: AAA1A23
        $l = fn() => Str::upper(Str::random(1));
        $d = fn() => (string)random_int(0,9);
        return $l().$l().$l().$d().$l().$d().$d();
    }

    private function chassi17(): string
    {
        // 17 caracteres, sem I/O/Q (mais comum)
        $alphabet = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789';
        $c = '';
        for ($i=0; $i<17; $i++) $c .= $alphabet[random_int(0, strlen($alphabet)-1)];
        return $c;
    }

    public function definition(): array
    {
        $marcasModelos = [
            'Fiat'      => ['Argo','Pulse','Cronos','Toro'],
            'Chevrolet' => ['Onix','Tracker','S10','Spin'],
            'Volkswagen'=> ['Polo','T-Cross','Nivus','Saveiro'],
            'Toyota'    => ['Corolla','Yaris','Hilux','Corolla Cross'],
            'Hyundai'   => ['HB20','Creta'],
            'Honda'     => ['Civic','HR-V','City'],
            'Renault'   => ['Kwid','Duster'],
            'Nissan'    => ['Kicks','Versa'],
            'Jeep'      => ['Renegade','Compass','Commander'],
        ];

        $marca  = array_rand($marcasModelos);
        $modelo = $marcasModelos[$marca][array_rand($marcasModelos[$marca])];

        $anos = range((int)date('Y')-10, (int)date('Y'));
        $combustiveis = ['gasolina','etanol','flex','diesel','eletrico','hibrido'];
        $cambios      = ['manual','automatico','cvt'];
        $cores        = ['preto','branco','prata','cinza','vermelho','azul','verde'];

        return [
            'user_id'      => User::inRandomOrder()->value('id') ?? User::factory(),
            'marca'        => $marca,
            'modelo'       => $modelo,
            'ano'          => (int)fake()->randomElement($anos),
            // 'placa'        => fake()->unique()->lexify('???').fake()->randomDigit().Str::upper(fake()->randomLetter()).fake()->randomNumber(2, true),
            // substitui por gerador fixo para garantir padrão
            'placa'        => fake()->unique()->passthrough($this->placaMercosul()),
            'chassi'       => fake()->unique()->passthrough($this->chassi17()),
            'km'           => fake()->numberBetween(0, 180_000),
            'valor_venda'  => fake()->randomFloat(2, 35_000, 350_000),
            'cambio'       => fake()->randomElement($cambios),
            'combustivel'  => fake()->randomElement($combustiveis),
            'cor'          => fake()->randomElement($cores),
        ];
    }
}
